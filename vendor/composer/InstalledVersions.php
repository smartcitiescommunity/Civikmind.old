<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;

/**
 * This class is copied in every Composer installed project and available to all
 *
 * To require it's presence, you can require `composer-runtime-api ^2.0`
 */
class InstalledVersions
{
    private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '1.0.0+no-version-set',
    'version' => '1.0.0.0',
    'aliases' => 
    array (
    ),
    'reference' => NULL,
    'name' => 'glpi/glpi',
  ),
  'versions' => 
  array (
    'blueimp/jquery-file-upload' => 
    array (
      'pretty_version' => 'v10.29.0',
      'version' => '10.29.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f41132833276acbf72c8e839e900215b0149945d',
    ),
    'brick/math' => 
    array (
      'pretty_version' => '0.8.14',
      'version' => '0.8.14.0',
      'aliases' => 
      array (
      ),
      'reference' => '6f7a46b5c3d487b277f38fbd17df57d348cace8a',
    ),
    'container-interop/container-interop' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '79cbf1341c22ec75643d841642dd5d6acd83bdb8',
    ),
    'container-interop/container-interop-implementation' => 
    array (
      'provided' => 
      array (
        0 => '^1.2',
      ),
    ),
    'elvanto/litemoji' => 
    array (
      'pretty_version' => '2.0.1',
      'version' => '2.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2cba2c87c505fe1d3a6e06ff4cc48d98af757521',
    ),
    'glpi/glpi' => 
    array (
      'pretty_version' => '1.0.0+no-version-set',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'guzzlehttp/guzzle' => 
    array (
      'pretty_version' => '6.5.5',
      'version' => '6.5.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '9d4290de1cfd701f38099ef7e183b64b4b7b0c5e',
    ),
    'guzzlehttp/promises' => 
    array (
      'pretty_version' => 'v1.3.1',
      'version' => '1.3.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a59da6cf61d80060647ff4d3eb2c03a2bc694646',
    ),
    'guzzlehttp/psr7' => 
    array (
      'pretty_version' => '1.6.1',
      'version' => '1.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '239400de7a173fe9901b9ac7c06497751f00727a',
    ),
    'htmlawed/htmlawed' => 
    array (
      'pretty_version' => '1.2.5',
      'version' => '1.2.5.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'iamcal/lib_autolink' => 
    array (
      'pretty_version' => 'v1.7',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b3a86d8437e5d635fb85b155a86288d94f6a924d',
    ),
    'laminas/laminas-cache' => 
    array (
      'pretty_version' => '2.9.0',
      'version' => '2.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f4746a868c3e2f2da63c19d23efac12b9d1bb554',
    ),
    'laminas/laminas-eventmanager' => 
    array (
      'pretty_version' => '3.2.1',
      'version' => '3.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ce4dc0bdf3b14b7f9815775af9dfee80a63b4748',
    ),
    'laminas/laminas-i18n' => 
    array (
      'pretty_version' => '2.10.3',
      'version' => '2.10.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '94ff957a1366f5be94f3d3a9b89b50386649e3ae',
    ),
    'laminas/laminas-json' => 
    array (
      'pretty_version' => '3.1.2',
      'version' => '3.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '00dc0da7b5e5018904c5c4a8e80a5faa16c2c1c6',
    ),
    'laminas/laminas-loader' => 
    array (
      'pretty_version' => '2.6.1',
      'version' => '2.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '5d01c2c237ae9e68bec262f339947e2ea18979bc',
    ),
    'laminas/laminas-mail' => 
    array (
      'pretty_version' => '2.12.5',
      'version' => '2.12.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ed5b36a0deef4ffafe6138c2ae9cafcffafab856',
    ),
    'laminas/laminas-mime' => 
    array (
      'pretty_version' => '2.7.4',
      'version' => '2.7.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e45a7d856bf7b4a7b5bd00d6371f9961dc233add',
    ),
    'laminas/laminas-serializer' => 
    array (
      'pretty_version' => '2.9.1',
      'version' => '2.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c1c9361f114271b0736db74e0083a919081af5e0',
    ),
    'laminas/laminas-servicemanager' => 
    array (
      'pretty_version' => '3.4.0',
      'version' => '3.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '044cb8e380682563fb277ed5f6de4f690e4e6239',
    ),
    'laminas/laminas-stdlib' => 
    array (
      'pretty_version' => '3.2.1',
      'version' => '3.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2b18347625a2f06a1a485acfbc870f699dbe51c6',
    ),
    'laminas/laminas-validator' => 
    array (
      'pretty_version' => '2.13.4',
      'version' => '2.13.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '93593684e70b8ed1e870cacd34ca32b0c0ace185',
    ),
    'laminas/laminas-zendframework-bridge' => 
    array (
      'pretty_version' => '1.0.4',
      'version' => '1.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fcd87520e4943d968557803919523772475e8ea3',
    ),
    'mexitek/phpcolors' => 
    array (
      'pretty_version' => 'v0.4',
      'version' => '0.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '89bf30473a68dc8845e46e9db3e536b969e18c11',
    ),
    'michelf/php-markdown' => 
    array (
      'pretty_version' => '1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c83178d49e372ca967d1a8c77ae4e051b3a3c75c',
    ),
    'monolog/monolog' => 
    array (
      'pretty_version' => '2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1cb1cde8e8dd0f70cc0fe51354a59acad9302084',
    ),
    'paragonie/random_compat' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'paragonie/sodium_compat' => 
    array (
      'pretty_version' => 'v1.14.0',
      'version' => '1.14.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a1cfe0b21faf9c0b61ac0c6188c4af7fd6fd0db3',
    ),
    'phpmailer/phpmailer' => 
    array (
      'pretty_version' => 'v6.1.6',
      'version' => '6.1.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c2796cb1cb99d7717290b48c4e6f32cb6c60b7b3',
    ),
    'psr/cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd11b50ad223250cf17b86e38383413f5a6764bf8',
    ),
    'psr/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
    ),
    'psr/container-implementation' => 
    array (
      'provided' => 
      array (
        0 => '^1.0',
      ),
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/http-message-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.3',
      'version' => '1.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '0f73288fd15629204f9d42b7055f72dacbe811fc',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0.0',
        1 => '1.0',
      ),
    ),
    'psr/simple-cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '408d5eafb83c57f6365a3ca330ff23aa4a5fa39b',
    ),
    'psr/simple-cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'ralouphie/getallheaders' => 
    array (
      'pretty_version' => '3.0.3',
      'version' => '3.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '120b605dfeb996808c31b6477290a714d356e822',
    ),
    'ramsey/collection' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '925ad8cf55ba7a3fc92e332c58fd0478ace3e1ca',
    ),
    'ramsey/uuid' => 
    array (
      'pretty_version' => '4.0.1',
      'version' => '4.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ba8fff1d3abb8bb4d35a135ed22a31c6ef3ede3d',
    ),
    'rhumsaa/uuid' => 
    array (
      'replaced' => 
      array (
        0 => '4.0.1',
      ),
    ),
    'rlanvin/php-rrule' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '931d53d162cd84b46f6fa388cb4ea916bec02c18',
    ),
    'sabre/dav' => 
    array (
      'pretty_version' => '4.1.3',
      'version' => '4.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b903eeedfbdcd6cab7935661ec6dc2d90cdf8a1e',
    ),
    'sabre/event' => 
    array (
      'pretty_version' => '5.1.2',
      'version' => '5.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c120bec57c17b6251a496efc82b732418b49d50a',
    ),
    'sabre/http' => 
    array (
      'pretty_version' => '5.1.1',
      'version' => '5.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd0aafede6961df6195ce7a8dad49296b0aaee22e',
    ),
    'sabre/uri' => 
    array (
      'pretty_version' => '2.2.1',
      'version' => '2.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f502edffafea8d746825bd5f0b923a60fd2715ff',
    ),
    'sabre/vobject' => 
    array (
      'pretty_version' => '4.3.3',
      'version' => '4.3.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '58f9f9b46a1080c0130bd86f4df9a568aacb9c79',
    ),
    'sabre/xml' => 
    array (
      'pretty_version' => '2.2.3',
      'version' => '2.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c3b959f821c19b36952ec4a595edd695c216bfc6',
    ),
    'scssphp/scssphp' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4363ddce8d750f055c436833dd77d83517946532',
    ),
    'sebastian/diff' => 
    array (
      'pretty_version' => '3.0.2',
      'version' => '3.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '720fcc7e9b5cf384ea68d9d930d480907a0c1a29',
    ),
    'simplepie/simplepie' => 
    array (
      'pretty_version' => '1.5.6',
      'version' => '1.5.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '1c68e14ca3ac84346b6e6fe3c5eedf725d0f92c6',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => 'v4.4.9',
      'version' => '4.4.9.0',
      'aliases' => 
      array (
      ),
      'reference' => '326b064d804043005526f5a0494cfb49edb59bb0',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-intl-idn' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '39d483bdf39be819deabf04ec872eb0b2410b531',
    ),
    'symfony/polyfill-php72' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a760d8964ff79ab9bf057613a5808284ec852ccc',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5e30b2799bc1ad68f7feb62b60a73743589438dd',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'v1.1.8',
      'version' => '1.1.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ffc7f5692092df31515df2a5ecf3b7302b3ddacf',
    ),
    'tecnickcom/tcpdf' => 
    array (
      'pretty_version' => '6.3.5',
      'version' => '6.3.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '19a535eaa7fb1c1cac499109deeb1a7a201b4549',
    ),
    'true/punycode' => 
    array (
      'pretty_version' => 'v2.1.1',
      'version' => '2.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a4d0c11a36dd7f4e7cd7096076cab6d3378a071e',
    ),
    'wapmorgan/cam' => 
    array (
      'replaced' => 
      array (
        0 => '1.0.2',
      ),
    ),
    'wapmorgan/unified-archive' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '74677318c376051fc695a3ab787e4471c00dce0f',
    ),
    'zendframework/zend-cache' => 
    array (
      'replaced' => 
      array (
        0 => '2.9.0',
      ),
    ),
    'zendframework/zend-eventmanager' => 
    array (
      'replaced' => 
      array (
        0 => '3.2.1',
      ),
    ),
    'zendframework/zend-i18n' => 
    array (
      'replaced' => 
      array (
        0 => '^2.10.1',
      ),
    ),
    'zendframework/zend-json' => 
    array (
      'replaced' => 
      array (
        0 => '3.1.2',
      ),
    ),
    'zendframework/zend-loader' => 
    array (
      'replaced' => 
      array (
        0 => '2.6.1',
      ),
    ),
    'zendframework/zend-mail' => 
    array (
      'replaced' => 
      array (
        0 => '^2.10.0',
      ),
    ),
    'zendframework/zend-mime' => 
    array (
      'replaced' => 
      array (
        0 => '^2.7.2',
      ),
    ),
    'zendframework/zend-serializer' => 
    array (
      'replaced' => 
      array (
        0 => '2.9.1',
      ),
    ),
    'zendframework/zend-servicemanager' => 
    array (
      'replaced' => 
      array (
        0 => '3.4.0',
      ),
    ),
    'zendframework/zend-stdlib' => 
    array (
      'replaced' => 
      array (
        0 => '3.2.1',
      ),
    ),
    'zendframework/zend-validator' => 
    array (
      'replaced' => 
      array (
        0 => '^2.13.0',
      ),
    ),
  ),
);
    private static $canGetVendors;
    private static $installedByVendor = array();

    /**
     * Returns a list of all package names which are present, either by being installed, replaced or provided
     *
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = array_keys($installed['versions']);
        }


        if (1 === \count($packages)) {
            return $packages[0];
        }

        return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
    }

    /**
     * Checks whether the given package is installed
     *
     * This also returns true if the package name is provided or replaced by another package
     *
     * @param  string $packageName
     * @return bool
     */
    public static function isInstalled($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['versions'][$packageName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the given package satisfies a version constraint
     *
     * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
     *
     *   Composer\InstalledVersions::satisfies(new VersionParser, 'foo/bar', '^2.3')
     *
     * @param VersionParser $parser      Install composer/semver to have access to this class and functionality
     * @param string        $packageName
     * @param string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
     *
     * @return bool
     */
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));

        return $provided->matches($constraint);
    }

    /**
     * Returns a version constraint representing all the range(s) which are installed for a given package
     *
     * It is easier to use this via isInstalled() with the $constraint argument if you need to check
     * whether a given version of a package is installed, and not just whether it exists
     *
     * @param  string $packageName
     * @return string Version constraint usable with composer/semver
     */
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            $ranges = array();
            if (isset($installed['versions'][$packageName]['pretty_version'])) {
                $ranges[] = $installed['versions'][$packageName]['pretty_version'];
            }
            if (array_key_exists('aliases', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
            }
            if (array_key_exists('replaced', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
            }
            if (array_key_exists('provided', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
            }

            return implode(' || ', $ranges);
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['pretty_version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
     */
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['reference'])) {
                return null;
            }

            return $installed['versions'][$packageName]['reference'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @return array
     * @psalm-return array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}
     */
    public static function getRootPackage()
    {
        $installed = self::getInstalled();

        return $installed[0]['root'];
    }

    /**
     * Returns the raw installed.php data for custom implementations
     *
     * @return array[]
     * @psalm-return array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>}
     */
    public static function getRawData()
    {
        return self::$installed;
    }

    /**
     * Lets you reload the static array from another file
     *
     * This is only useful for complex integrations in which a project needs to use
     * this class but then also needs to execute another project's autoloader in process,
     * and wants to ensure both projects have access to their version of installed.php.
     *
     * A typical case would be PHPUnit, where it would need to make sure it reads all
     * the data it needs from this class, then call reload() with
     * `require $CWD/vendor/composer/installed.php` (or similar) as input to make sure
     * the project in which it runs can then also use this class safely, without
     * interference between PHPUnit's dependencies and the project's dependencies.
     *
     * @param  array[] $data A vendor/composer/installed.php data set
     * @return void
     *
     * @psalm-param array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>} $data
     */
    public static function reload($data)
    {
        self::$installed = $data;
        self::$installedByVendor = array();
    }

    /**
     * @return array[]
     */
    private static function getInstalled()
    {
        if (null === self::$canGetVendors) {
            self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
        }

        $installed = array();

        if (self::$canGetVendors) {
            // @phpstan-ignore-next-line
            foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
                if (isset(self::$installedByVendor[$vendorDir])) {
                    $installed[] = self::$installedByVendor[$vendorDir];
                } elseif (is_file($vendorDir.'/composer/installed.php')) {
                    $installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
                }
            }
        }

        $installed[] = self::$installed;

        return $installed;
    }
}
