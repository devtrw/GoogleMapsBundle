class { "lamp":
    developmentEnvironment => true,
    serverName => 'googlemapsbundle.com',
    phpModules => ["curl", "intl", "phing"]
}

file { [
    "/tmp/reports",
    "/tmp/reports/app",
    "/tmp/reports/app/config"
] :
    ensure => "directory"
}
-> lamp::app { "reports.googlemapsbundle":
    sourceLocation   => "/tmp/reports",
    apacheDirectives => { "Options" => "+Indexes +FollowSymLinks" },
    createUser       => false,
    createDatabase   => false,
    serverName       => "reports.googlemapsbundle.com",
    symfony2App      => true,
    symfony2Secret   => "not-acutally-needed"
}