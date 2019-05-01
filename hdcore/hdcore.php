<?php

require_once('core_api.php');

function getApiClient($params)
{
    return new CoreClient(
        $params['configoption1'],
        $params['configoption3'],
        array('endpoint' => $params['configoption2'])
    );
}

function hdcore_ConfigOptions()
{
    return array(
        'public_key' => array(
            'FriendlyName' => 'Public Key',
            'Type'         => 'text',
            'Size'         => '60'
        ),
        'url' => array(
            'FriendlyName' => 'HostDime Core API URL',
            'Type'         => 'text',
            'Size'         => '255',
            'Default'      => 'https://api.hostdime.com/v1'
        ),
        'private_key' => array(
            'FriendlyName' => 'Private Key',
            'Type'         => 'text',
            'Size'         => '50'
        )
    );
}

function hdcore_SuspendAccount($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('server.suspend', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcore_UnsuspendAccount($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('server.unsuspend', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcore_AdminCustomButtonArray()
{
    return array(
        "Cycle Power"     => "reboot",
        "Turn Off Server" => "turnoff",
        "Turn On Server"  => "turnon"
    );
}

function hdcore_ClientAreaCustomButtonArray()
{
    return array(
        "Cycle Power"     => "reboot",
        "Turn Off Server" => "turnoff",
        "Turn On Server"  => "turnon"
    );
}

function hdcore_reboot($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('server.power.cycle', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcore_turnoff($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('server.power.off', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcore_turnon($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('server.power.on', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcore_ClientArea($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "";

    $server = $api->call('server.get', array(
        'cuid' => $api_id
    ));

    // Error
    if (isset($server['error']['code'])) {
        return $server['error']['message'];
    }

    $vars = array(
        'server' => $server['response']
    );

    $ip_addresses = null;

    // Only pull bandwidth images and IPs for active and suspended services
    $active_status = array('active','suspended','clientsuspend');
    if (in_array($server['response']['status'], $active_status)) {
        $day = $api->call('server.bandwidth', array(
            'cuid'        => $api_id,
            'graph_type'  => 'port',
            'time_period' => 'day'
        ));

        $week = $api->call('server.bandwidth', array(
            'cuid'        => $api_id,
            'graph_type'  => 'port',
            'time_period' => 'week'
        ));

        $month = $api->call('server.bandwidth', array(
            'cuid'        => $api_id,
            'graph_type'  => 'port',
            'time_period' => 'month'
        ));

        $year = $api->call('server.bandwidth', array(
            'cuid'        => $api_id,
            'graph_type'  => 'port',
            'time_period' => 'year'
        ));

        $ip_addresses = $api->call('server.rdns.list', array(
            'cuid'        => $api_id
        ));

        $control_panel = (stripos($server['control_panel'], 'cPanel') !== false ? true : false);

        $vars = array_merge(
            $vars,
            array(
                'day_graph'     => $day['response']['data'],
                'week_graph'    => $week['response']['data'],
                'month_graph'   => $month['response']['data'],
                'year_graph'    => $year['response']['data'],
                'ip_addresses'  => $ip_addresses['response'],
                'control_panel' => $control_panel
            )
        );
    }

    return array(
        'templatefile' => 'clientarea',
        'vars'         => $vars
    );

}

function hdcore_rdns($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "";

    foreach ($_POST['ptr'] as $ip => $ptr) {
        $api->call('server.rdns.update', array(
            'ip'  => $ip,
            'ptr' => $ptr
        ));
    }

    return 'success';
}
