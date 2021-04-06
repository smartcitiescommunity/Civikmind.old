<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2021 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginFormcreatorKnowbase {

   /**
    * Show the list of forms to be displayed to the end-user
    */
   public function showList() {
      echo '<div class="center" id="plugin_formcreator_wizard">';

      echo '<div class="plugin_formcreator_card">';
      $this->showWizard();
      echo '</div>';

      echo '</div>';
   }

   public function showServiceCatalog() {
      echo "<div id='formcreator_servicecatalogue'>";

      // show wizard
      echo '<div id="plugin_formcreator_wizard">';
      $this->showWizard(true);
      echo '</div>';

      echo '</div>'; // formcreator_servicecatalogue
   }

   public function showWizard($service_catalog = false) {
      echo '<div id="plugin_formcreator_kb_categories">';
      echo '<div><h2>'._n("Category", "Categories", 2, 'formcreator').'</h2></div>';
      echo '<div><a href="#" id="kb_seeall">' . __('see all', 'formcreator') . '</a></div>';
      echo '</div>';

      echo '<div id="plugin_formcreator_wizard_right">';

      // hook display central (for alert plugin)
      if ($service_catalog) {
         echo "<div id='plugin_formcreator_display_central'>";
         Plugin::doHook('display_central');
         echo "</div>";
      }

      echo '<div id="plugin_formcreator_searchBar">';
      $this->showSearchBar();
      echo '</div>';
      echo '<div id="plugin_formcreator_wizard_forms">';
      echo '</div>';
      echo '</div>';
   }

   protected function showSearchBar() {
      echo '<form name="plugin_formcreator_search" onsubmit="javascript: return false;" >';
      echo '<input type="text" name="words" id="plugin_formcreator_search_input" required/>';
      echo '<span id="plugin_formcreator_search_input_bar"></span>';
      echo '<label for="plugin_formcreator_search_input">'.__('Please, describe your need here', 'formcreator').'</label>';
      echo '</form>';
   }

   /**
    * @see Knowbase::getJstreeCategoryList()
    *
    * @param int $rootId id of the subtree root
    * @param bool $helpdeskHome
    *
    * @return array Tree of form categories as nested array
    */
   public static function getCategoryTree() {
      global $DB;

      $cat_table = KnowbaseItemCategory::getTable();
      $cat_fk  = KnowbaseItemCategory::getForeignKeyField();

      $kbitem_visibility_crit = KnowbaseItem::getVisibilityCriteria(true);

      $items_subquery = new QuerySubQuery(
         array_merge_recursive(
            [
               'SELECT' => ['COUNT DISTINCT' => KnowbaseItem::getTableField('id') . ' as cpt'],
               'FROM'   => KnowbaseItem::getTable(),
               'WHERE'  => [
                  KnowbaseItem::getTableField($cat_fk) => new QueryExpression(
                     DB::quoteName(KnowbaseItemCategory::getTableField('id'))
                  ),
               ]
            ],
            $kbitem_visibility_crit
         ),
         'items_count'
      );

      $cat_iterator = $DB->request([
         'SELECT' => [
            KnowbaseItemCategory::getTableField('id'),
            KnowbaseItemCategory::getTableField('name'),
            KnowbaseItemCategory::getTableField($cat_fk),
            $items_subquery,
         ],
         'FROM' => $cat_table,
         'ORDER' => [
            KnowbaseItemCategory::getTableField('level') . ' DESC',
            KnowbaseItemCategory::getTableField('name'),
         ]
      ]);

      $inst = new KnowbaseItemCategory;
      $categories = [];
      foreach ($cat_iterator as $category) {
         if (DropdownTranslation::canBeTranslated($inst)) {
            $tname = DropdownTranslation::getTranslatedValue(
               $category['id'],
               $inst->getType()
            );
            if (!empty($tname)) {
               $category['name'] = $tname;
            }
         }
         $categories[] = $category;
      }

      // Remove categories that have no items and no children
      // Requires category list to be sorted by level DESC
      foreach ($categories as $index => $category) {
         $children = array_filter(
            $categories,
            function ($element) use ($category, $cat_fk) {
               return $category['id'] == $element[$cat_fk];
            }
         );

         if (empty($children) && 0 == $category['items_count']) {
            unset($categories[$index]);
            continue;
         }
         $categories[$index]['subcategories'] = [];
      }

      // Create root node
      $nodes = [
         'name'            => '',
         'id'              => 0,
         'parent'          => 0,
         'subcategories'   => [],
      ];
      $flat = [
         0 => &$nodes,
      ];

      // Build from root node to leaves
      $categories = array_reverse($categories);
      foreach ($categories as $item) {
         $flat[$item['id']] = $item;
         $flat[$item[$cat_fk]]['subcategories'][] = &$flat[$item['id']];
      }

      return $nodes;
   }

   public static function getFaqItems($rootCategory = 0, $keywords = '') {
      global $DB;

      $table_cat          = getTableForItemType('KnowbaseItemCategory');
      $table_item         = getTableForItemType('KnowbaseItem');
      $selectedCategories = [];
      $selectedCategories = getSonsOf($table_cat, $rootCategory);
      $selectedCategories[$rootCategory] = $rootCategory;

      $query_faqs = KnowbaseItem::getListRequest([
         'faq'      => '1',
         'contains' => $keywords,
      ], 'search');
      if (count($selectedCategories) > 0) {
         $query_faqs['WHERE'][] = [
            "$table_item.knowbaseitemcategories_id" => $selectedCategories,
         ];
      }

      $formList = [];
      $result_faqs = $DB->request($query_faqs);
      foreach ($result_faqs as $faq) {
         $formList[] = [
            'id'           => $faq['id'],
            'name'         => $faq['name'],
            'icon'         => '',
            'icon_color'   => '',
            'background_color'   => '',
            'description'  => '',
            'type'         => 'faq',
            'usage_count'  => $faq['view'],
            'is_default'   => false
         ];
      }

      return ['default' => [], 'forms' => $formList];
   }
}