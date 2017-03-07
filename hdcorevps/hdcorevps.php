<?php

require_once('core_vps_api.php');

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
    $api = new CoreVpsClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreVpsClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreVpsClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreVpsClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreVpsClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreVpsClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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


    $dns_html = "";
    foreach($vps['response']['dns'] as $name_server) {
        $dns_html[] = "{$name_server['dns']} {$name_server['ip']}";
    }
    $dns_html = implode("<br />", $dns_html);

    $html = <<<"EOT"
<style type="text/css">
    .module-client-area .row {
        padding-bottom: 15px;
    }
</style>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>ID</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['cuid']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Memory</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['memory']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Operating System</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['os']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Control Panel</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['control_panel']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Main IP</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['ip_main']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>DNS</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$dns_html}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>SSH Port</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['ssh_port']}
        <a href="ssh://{$vps['response']['ip_main']}:{$vps['response']['ssh_port']}" class="btn btn-primary">
        <i class="fa fa-terminal"></i>
        SSH to Server
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Disk Usage</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps['response']['disk_usage']} MB / {$vps['response']['disk_quota']} MB
    </div>
</div>
EOT;

    if (in_array($vps['response']['status'], $active_status)) {
        $html .= <<<"EOB"
<div class="row">
    <h4>Daily Graph</h4>
    <img src="data:image/png;base64,{$day['response']['data']}" />
</div>
<div class="row">
    <h4>Weekly Graph</h4>
    <img src="data:image/png;base64,{$week['response']['data']}" />
</div>
<div class="row">
    <h4>Monthly Graph</h4>
    <img src="data:image/png;base64,{$month['response']['data']}" />
</div>
<div class="row">
    <h4>Yearly Graph</h4>
    <img src="data:image/png;base64,{$year['response']['data']}" />
</div>
EOB;
    }

    return $html;

}
