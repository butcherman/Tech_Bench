<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">Welcome To The Tech Bench</h1>
    </div>
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="text-center">New Site Setup</h2>
                <p>
                    Welcome to the Tech Bench.  This is a custom Content Management System (CMS) that is designed to help service technicians keep track of customer information and system information.  This web application is meant to help field technicians store information in a safe manner for all registered technicans to access.  The goal of this application is to improve technician productivity and communication.
                </p>
                <p>
                    Please note that this web application is designed for service technicians that do field work and need to keep track of system information.  It does not handle any billing information or service orders.
                </p>
                <p>
                    After reviewing the following prerequisites, press the Get Started button below to configure the application.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <ul class="list-group">
                    <li>This application requires the following:
                        <ul>
                            <li>Apache Web Server</li>
                            <li>PHP5 or higher</li>
                            <li>MySQL Database</li>
                        </ul>
                    </li>
                    <li>It is necessary to make sure that the following directories have write access:
                        <ul>
                            <li><img src="/source/img/<?= $data['logs']; ?>" alt="Check Log Folder" />WebRoot/logs</li>
                            <li><img src="/source/img/<?= $data['config']; ?>" alt="Check Config Folder" />WebRoot/configWebRoot/logs</li>
                            <li><img src="/source/img/<?= $data['files']; ?>" alt="Check Files Folder" />WebRoot/_filesWebRoot/logs</li>
                        </ul>
                    </li>
                    <li>It is highly recommended to use HTTPS connections for secure encrypted communication between the web browser and server.</li>
                    <li>Note: For more information, please see README file</li>
                </ul>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <a href="/setup/begin" class="btn btn-default btn-block">Get Started</a>
            </div>
        </div>
    </div>
</div>