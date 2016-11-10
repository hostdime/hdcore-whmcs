<?php

require_once('core_api.php');

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
    $api = new CoreClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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
    $api = new CoreClient(
                $params['configoption1'],
                $params['configoption3'],
                array('endpoint' => $params['configoption2'])
           );

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

    // Only pull bandwidth images for active and suspended services
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
    }

    $dns_html = "";
    foreach($server['response']['dns'] as $name_server) {
        $dns_html[] = "{$name_server['dns']} {$name_server['ip']}";
    }
    $dns_html = implode("<br />", $dns_html);

    $hdd_html = "";
    foreach($server['response']['hard_drives'] as $drive) {
        $hdd_html[] = "{$drive['size']} {$drive['type']}";
    }
    $hdd_html = implode("<br />", $hdd_html);

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
        {$server['response']['label']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Chassis Type</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server['response']['type']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Main IP</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server['response']['ip_main']}
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
        <strong>CPU</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server['response']['cpu']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Memory</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server['response']['memory']}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>SSH Port</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server['response']['ssh_port']}
        <a href="ssh://{$server['response']['ip_main']}:{$server['response']['ssh_port']}" class="btn btn-primary">
        <i class="fa fa-terminal"></i>
        SSH to Server
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Hard Disks</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$hdd_html}
    </div>
</div>
EOT;

    if (in_array($server['response']['status'], $active_status)) {
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
