<?php

namespace Groar\JiraAuthentication;

class Authenticator {
    private $jiraBase;
    public function __construct($jiraBase) {
        $this->jiraBase = $jiraBase;
    }
    public function auth($username, $password, $expectedGroup) {
        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode(implode(':', array($username, $password)))
            )
        ));
        $content = json_decode(@file_get_contents($this->jiraBase.'/rest/api/2/myself?expand=groups', false, $context));

        if($content && isset($content->groups->items)) {
            foreach($content->groups->items AS $group) {
                if($group->name == $expectedGroup) {
                    return true;
                }
            }
        }
        return false;
    }
}