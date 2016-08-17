# HTTP Basic Auth with Jira credentials

To use AuthExternal in your apache configuration you need the authnz-external mod. Install on Ubuntu/Debian with:
        
        apt-get install libapache2-mod-authnz-external
        cd /usr/local/bin/
        git clone https://github.com/Degola/jira-authentication-apache-mod-authnz-external
        cd jira-authentication-apache-mod-authnz-external
        composer update

Example HTTP Configuration:

        <Location />
                AuthType Basic
                AuthName "Restricted"
                AuthBasicProvider external
                AuthExternal jira-backend
                require valid-user
        </Location>

        AddExternalAuth jira-backend "/usr/local/bin/jira-auth/bin/jira-auth.php https://your-jira-url.com/jira/ your-logstash-server.com:1234 i-have-access-group"
        SetExternalAuthMethod jira-backend pipe

i-have-access-group is the corresponding jira group which has to be assigned to the user who tries to login.
