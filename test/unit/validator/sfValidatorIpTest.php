<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$validV4Ips = [
    '0.0.0.0',
    '10.0.0.0',
    '123.45.67.178',
    '172.16.0.0',
    '192.168.1.0',
    '224.0.0.1',
    '255.255.255.255',
    '127.0.0.0',
];

$invalidV4Ips = [
    '0',
    '0.0',
    '0.0.0',
    '256.0.0.0',
    '0.256.0.0',
    '0.0.256.0',
    '0.0.0.256',
    '-1.0.0.0',
    'foobar',
];

$invalidPrivateV4Ips = [
    '10.0.0.0',
    '172.16.0.0',
    '192.168.1.0',
];

$invalidReservedV4Ips = [
    '0.0.0.0',
    '240.0.0.1',
    '255.255.255.255',
];

$validV6Ips = [
    '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
    '2001:0DB8:85A3:0000:0000:8A2E:0370:7334',
    '2001:0Db8:85a3:0000:0000:8A2e:0370:7334',
    'fdfe:dcba:9876:ffff:fdc6:c46b:bb8f:7d4c',
    'fdc6:c46b:bb8f:7d4c:fdc6:c46b:bb8f:7d4c',
    'fdc6:c46b:bb8f:7d4c:0000:8a2e:0370:7334',
    'fe80:0000:0000:0000:0202:b3ff:fe1e:8329',
    'fe80:0:0:0:202:b3ff:fe1e:8329',
    'fe80::202:b3ff:fe1e:8329',
    '0:0:0:0:0:0:0:0',
    '::',
    '0::',
    '::0',
    '0::0',
    // IPv4 mapped to IPv6
    '2001:0db8:85a3:0000:0000:8a2e:0.0.0.0',
    '::0.0.0.0',
    '::255.255.255.255',
    '::123.45.67.178',
];

$invalidV6Ips = [
    'z001:0db8:85a3:0000:0000:8a2e:0370:7334',
    'fe80',
    'fe80:8329',
    'fe80:::202:b3ff:fe1e:8329',
    'fe80::202:b3ff::fe1e:8329',
    // IPv4 mapped to IPv6
    '2001:0db8:85a3:0000:0000:8a2e:0370:0.0.0.0',
    '::0.0',
    '::0.0.0',
    '::256.0.0.0',
    '::0.256.0.0',
    '::0.0.256.0',
    '::0.0.0.256',
];

$invalidPrivateV6Ips = [
    'fdfe:dcba:9876:ffff:fdc6:c46b:bb8f:7d4c',
    'fdc6:c46b:bb8f:7d4c:fdc6:c46b:bb8f:7d4c',
    'fdc6:c46b:bb8f:7d4c:0000:8a2e:0370:7334',
];

$t = new lime_test(180, new lime_output_color());

$t->diag('testing against empty IP');

$v = new sfValidatorIp(['required' => false]);

foreach ([null, ''] as $ip) {
    try {
        $v->clean($ip);
        $t->pass('Empty IP is considered as valid');
    } catch (sfValidatorError $e) {
        $t->fail('Empty IP is considered as valid');
    }
}

$t->diag('testing against valid IP all');
foreach (array_merge($validV4Ips, $validV6Ips) as $ip) {
    try {
        $v->clean($ip);
        $t->pass(sprintf('%s is considered as valid ip', $ip));
    } catch (sfValidatorError $e) {
        $t->fail(sprintf('%s is considered as valid ip', $ip));
    }
}

foreach ([sfValidatorIp::IP_V4 => $validV4Ips, sfValidatorIp::IP_V6 => $validV6Ips] as $version => $ips) {
    $t->diag('testing against valid IP V'.$version);
    $v->setOption('version', $version);
    foreach ($ips as $ip) {
        try {
            $v->clean($ip);
            $t->pass(sprintf('%s is considered as valid ip', $ip));
        } catch (sfValidatorError $e) {
            $t->fail(sprintf('%s is considered as valid ip', $ip));
        }
    }
}

$t->diag('testing against invalid IP all');
foreach (array_merge($invalidV4Ips, $invalidV6Ips) as $ip) {
    try {
        $v->clean($ip);
        $t->fail(sprintf('%s is not considered as valid ip', $ip));
    } catch (sfValidatorError $e) {
        $t->pass(sprintf('%s is not considered as valid ip', $ip));
    }
}

foreach ([sfValidatorIp::IP_V4 => $invalidV4Ips, sfValidatorIp::IP_V6 => $invalidV6Ips] as $version => $ips) {
    $t->diag('testing against invalid IP V'.$version);
    $v->setOption('version', $version);
    foreach ($ips as $ip) {
        try {
            $v->clean($ip);
            $t->fail(sprintf('%s is not considered as valid ip', $ip));
        } catch (sfValidatorError $e) {
            $t->pass(sprintf('%s is not considered as valid ip', $ip));
        }
    }
}

foreach ([sfValidatorIp::IP_V4_NO_PRIV => $invalidPrivateV4Ips, sfValidatorIp::IP_V6_NO_PRIV => $invalidPrivateV6Ips] as $version => $ips) {
    $t->diag('testing against invalid IP V'.$version);
    $v->setOption('version', $version);
    foreach ($ips as $ip) {
        try {
            $v->clean($ip);
            $t->fail(sprintf('%s is not considered as valid ip', $ip));
        } catch (sfValidatorError $e) {
            $t->pass(sprintf('%s is not considered as valid ip', $ip));
        }
    }
}

foreach ([sfValidatorIp::IP_V4_NO_RES => $invalidReservedV4Ips, sfValidatorIp::IP_V6_NO_RES => $invalidV6Ips] as $version => $ips) {
    $t->diag('testing against invalid IP V'.$version);
    $v->setOption('version', $version);
    foreach ($ips as $ip) {
        try {
            $v->clean($ip);
            $t->fail(sprintf('%s is not considered as valid ip', $ip));
        } catch (sfValidatorError $e) {
            $t->pass(sprintf('%s is not considered as valid ip', $ip));
        }
    }
}

foreach ([sfValidatorIp::IP_V4_ONLY_PUBLIC => array_merge($invalidReservedV4Ips, $invalidPrivateV4Ips), sfValidatorIp::IP_V6_ONLY_PUBLIC => array_merge($invalidPrivateV6Ips, $invalidV6Ips)] as $version => $ips) {
    $t->diag('testing against invalid IP V'.$version);
    $v->setOption('version', $version);
    foreach ($ips as $ip) {
        try {
            $v->clean($ip);
            $t->fail(sprintf('%s is not considered as valid ip', $ip));
        } catch (sfValidatorError $e) {
            $t->pass(sprintf('%s is not considered as valid ip', $ip));
        }
    }
}

$t->diag('testing against invalid private IP all');

$v->setOption('version', sfValidatorIp::IP_ALL_NO_PRIV);
foreach (array_merge($invalidPrivateV4Ips, $invalidPrivateV6Ips) as $ip) {
    try {
        $v->clean($ip);
        $t->fail(sprintf('%s is not considered as valid ip', $ip));
    } catch (sfValidatorError $e) {
        $t->pass(sprintf('%s is not considered as valid ip', $ip));
    }
}

$t->diag('testing against invalid reserved IP all');

$v->setOption('version', sfValidatorIp::IP_ALL_NO_RES);
foreach (array_merge($invalidReservedV4Ips, $invalidV6Ips) as $ip) {
    try {
        $v->clean($ip);
        $t->fail(sprintf('%s is not considered as valid ip', $ip));
    } catch (sfValidatorError $e) {
        $t->pass(sprintf('%s is not considered as valid ip', $ip));
    }
}

$t->diag('testing against invalid public IP all');

$v->setOption('version', sfValidatorIp::IP_ALL_ONLY_PUBLIC);
foreach (array_merge($invalidReservedV4Ips, $invalidV6Ips, $invalidPrivateV4Ips, $invalidPrivateV6Ips) as $ip) {
    try {
        $v->clean($ip);
        $t->fail(sprintf('%s is not considered as valid ip', $ip));
    } catch (sfValidatorError $e) {
        $t->pass(sprintf('%s is not considered as valid ip', $ip));
    }
}
