<?php

require_once('core_vps_api.php');

function getApiClient($params)
{
    return new CoreVpsClient(
        $params['configoption1'],
        $params['configoption3'],
        array('endpoint' => $params['configoption2'])
    );
}

function hdcorevps_ConfigOptions()
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

function hdcorevps_SuspendAccount($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('vps.suspend', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcorevps_UnsuspendAccount($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('vps.unsuspend', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcorevps_AdminCustomButtonArray()
{
    return array(
        "Cycle Power"  => "reboot",
        "Turn Off VPS" => "turnoff",
        "Turn On VPS"  => "turnon"
    );
}

function hdcorevps_ClientAreaCustomButtonArray()
{
    return array(
        "Cycle Power"  => "reboot",
        "Turn Off VPS" => "turnoff",
        "Turn On VPS"  => "turnon"
    );
}

function hdcorevps_reboot($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('vps.restart', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcorevps_turnoff($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('vps.stopf', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcorevps_turnon($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "No API ID is configured for this service";

    $result = $api->call('vps.start', array('cuid' => $api_id));

    // Error
    if (isset($result['error']['code'])) {
        return $result['error']['message'];
    }

    return "success";
}

function hdcorevps_ClientArea($params)
{
    $api = getApiClient($params);

    $api_id = $params['customfields']['API ID'];
    if (empty($api_id))
        return "";

    $vps = $api->call('vps.get', array(
        'cuid' => $api_id
    ));

    // Error
    if (isset($vps['error']['code'])) {
        return $vps['error']['message'];
    }

    $vars = array(
        'vps' => $vps['response']
    );

    $active_status = array('active','suspended','clientsuspend');
    if (in_array($vps['response']['status'], $active_status)) {
        $day = $api->call('vps.graphs', array(
            'cuid'       => $api_id,
            'type'       => 'bandwidth',
            'date_range' => array(date('Y-m-d', strtotime('-1 day')), date('Y-m-d'))
        ));

        $week = $api->call('vps.graphs', array(
            'cuid'       => $api_id,
            'type'       => 'bandwidth',
            'date_range' => array(date('Y-m-d', strtotime('-7 days')), date('Y-m-d'))
        ));

        $month = $api->call('vps.graphs', array(
            'cuid'       => $api_id,
            'type'       => 'bandwidth',
            'date_range' => array(date('Y-m-d', strtotime('-1 month')), date('Y-m-d'))
        ));

        $year = $api->call('vps.graphs', array(
            'cuid'       => $api_id,
            'type'       => 'bandwidth',
            'date_range' => array(date('Y-m-d', strtotime('-1 year')), date('Y-m-d'))
        ));

        $vars = array_merge(
            $vars,
            array(
                'day_graph'   => $day['response']['data'],
                'week_graph'  => $week['response']['data'],
                'month_graph' => $month['response']['data'],
                'year_graph'  => $year['response']['data']
            )
        );
    }

    return array(
        'templatefile' => 'clientarea',
        'vars'         => $vars
    );

}
