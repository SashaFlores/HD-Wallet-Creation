<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '91e1a879b997a234a06a3a022f094b40f3762043',
    'name' => 'dan-da/hd-wallet-derive',
  ),
  'versions' => 
  array (
    'bitwasp/bech32' => 
    array (
      'pretty_version' => 'v0.0.1',
      'version' => '0.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e1ea58c848a4ec59d81b697b3dfe9cc99968d0e7',
    ),
    'bitwasp/bitcoin' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '8782bc6c470e342d4e0cb67eb036974f133b950b',
    ),
    'bitwasp/buffertools' => 
    array (
      'pretty_version' => '0.5.x-dev',
      'version' => '0.5.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '133746d0b514e0016d8479b54aa97475405a9f1f',
    ),
    'composer/semver' => 
    array (
      'pretty_version' => '1.x-dev',
      'version' => '1.9999999.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '38276325bd896f90dfcfe30029aa5db40df387a7',
    ),
    'dan-da/coinparams' => 
    array (
      'pretty_version' => 'v0.2.9',
      'version' => '0.2.9.0',
      'aliases' => 
      array (
      ),
      'reference' => '35e0eb64f0e74f1013833ada1ad55946efb072ed',
    ),
    'dan-da/hd-wallet-derive' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '91e1a879b997a234a06a3a022f094b40f3762043',
    ),
    'dan-da/strictmode-php' => 
    array (
      'pretty_version' => 'v1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c58bcf128d658ecbaa48a619f00e3082279a032d',
    ),
    'dan-da/tester-php' => 
    array (
      'pretty_version' => 'v1.0.4',
      'version' => '1.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '46ab3b581e4370b19999f96f6a3b691c7a4d850f',
    ),
    'dan-da/texttable-php' => 
    array (
      'pretty_version' => 'v1.0.3',
      'version' => '1.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c7907393f3442049d0ad8ee1167e171591cf84aa',
    ),
    'fgrosse/phpasn1' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd1978f7abd580f3fc33561e7f71d4c12c7531fad',
    ),
    'lastguest/murmurhash' => 
    array (
      'pretty_version' => '2.0.0',
      'version' => '2.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4fb7516f67e695e5d7fa129d1bbb925ec0ebe408',
    ),
    'mdanter/ecc' => 
    array (
      'pretty_version' => 'v0.5.2',
      'version' => '0.5.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b95f25cc1bacc83a9f0ccd375900b7cfd343029e',
    ),
    'olegabr/keccak' => 
    array (
      'pretty_version' => '1.0.4',
      'version' => '1.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '280978604726639f933326f3be6aaa2eb491398f',
    ),
    'pleonasm/merkle-tree' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '9ddc9d0a0e396750fada378f3aa90f6c02dd56a1',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
