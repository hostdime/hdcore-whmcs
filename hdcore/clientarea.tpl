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
        {$server.cuid}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Chassis Type</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server.type}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Main IP</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server.ip_main}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>DNS</strong>
    </div>
    <div class="col-sm-7 text-left">
        {foreach from=$server.dns item=name_server}
            {$name_server.dns} {$ns.ip} <br />
        {/foreach}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>CPU</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server.cpu}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Memory</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server.memory}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>SSH Port</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$server.ssh_port}
        <a href="ssh://{$server.ip_main}:{$server.ssh_port}" class="btn btn-primary">
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
        {foreach from=$server.hard_drives item=drive}
            {$drive.size} {$drive.type}<br />
        {/foreach}
    </div>
</div>


{if $day_graph}
    <div class="row">
        <h4>Daily Graph</h4>
        <img src="data:image/png;base64,{$day_graph}" />
    </div>
    <div class="row">
        <h4>Weekly Graph</h4>
        <img src="data:image/png;base64,{$week_graph}" />
    </div>
    <div class="row">
        <h4>Monthly Graph</h4>
        <img src="data:image/png;base64,{$month_graph}" />
    </div>
    <div class="row">
        <h4>Yearly Graph</h4>
        <img src="data:image/png;base64,{$year_graph}" />
    </div>
{/if}