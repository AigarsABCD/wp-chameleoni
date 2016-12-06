<?php

class chameleoni {

    private $action = 'postxml';
    private $authkey = 'Guest';
    private $authpassword = 'KgwLLm7TL6G6';

    private $apikey = 'D12E9CF3-F742-47FC-97CB-295F4488C2FA';
    private $username = 'David';

    private function postxml($xml) {

        file_put_contents(plugin_dir_path( __FILE__ ) . "send.txt", print_r($xml, true), FILE_APPEND);
        $xml = str_replace('utf-8', 'utf-16', $xml);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://jobs.chameleoni.com/PostXML/PostXml.aspx');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded",]);

        $body = ['AuthKey' => $this->authkey, 'Xml' => $xml, 'Action' => $this->action, 'AuthPassword' => $this->authpassword,];
        $body = http_build_query($body);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $resp = curl_exec($ch);

        $return = array();

        if (!$resp) {
            $return['status'] = curl_errno($ch);
            $return['response'] = curl_error($ch);
        } else {
            $return['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $return['response'] = str_replace('utf-16', 'utf-8', $resp);
        }

        file_put_contents(plugin_dir_path( __FILE__ ) . "return.txt", print_r($resp, true), FILE_APPEND);

        curl_close($ch);

        return $return;

    }


    public function TitleList() {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'TitleList';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;
        $xml->Filter = '';
        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
    }

    public function CandidateRegister($TitleId = 1, $Forename, $Surname, $Email, $WebPassword = '', $HomeTelNo = '', $MobileTelNo = '', $WorkTelNo = '') {
        if (empty($TitleId)) {
            $TitleId = 1;
        }
        if (empty($Forename)) {
            throw new Exception("Forename empty");
        }
        if (empty($Surname)) {
            throw new Exception("Surname empty");
        }
        if (empty($Email)) {
            throw new Exception("Email empty");
        }
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'CandidateRegister';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;
        $inputdata = $xml->addChild('InputData');

        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'TitleId');
        $input->addAttribute('Value', $TitleId);

        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'Forename');
        $input->addAttribute('Value', $Forename);

        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'Surname');
        $input->addAttribute('Value', $Surname);

        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'Email');
        $input->addAttribute('Value', $Email);
        if (!empty($WebPassword)) {
            $input = $inputdata->addChild('Input');
            $input->addAttribute('Name', 'WebPassword');
            $input->addAttribute('Value', $WebPassword);
        }


        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'HomeTelNo');
        $input->addAttribute('Value', $HomeTelNo);


        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'MobileTelNo');
        $input->addAttribute('Value', $MobileTelNo);


        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'WorkTelNo');
        $input->addAttribute('Value', $WorkTelNo);


        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
        // **********************************************
        // *    Returns
        // *
        // *    [status] => 200
        // *    [response] => SimpleXMLElement Object
        // *    (
        // *        [TimeNow] => 2016-12-05T135700Z
        // *        [ResponseId] => 2380857
        // *        [Status] => Pass
        // *        [Message] => Nothing to say
        // *        [ContactId] => 17077800
        // *    )
        // *
        // ***********************************************
    }

    public function CheckEmail($Email) {
        if (empty($Email)) {
            throw new Exception("Email empty");
        }
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'CheckEmail';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;

        $inputdata = $xml->addChild('InputData');
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'Email');
        $input->addAttribute('Value', $Email);

        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
        // **********************************************
        // *    Returns
        // *
        // *    [status] => 200
        // *    [response] => SimpleXMLElement Object
        // *        (
        // *        [TimeNow] => 2016-12-05T135300Z
        // *
        // *        [ResponseId] => 2380855
        // *        [Status] => Pass
        // *        [Message] => Nothing to say
        // *        [ContactCount] => 1
        // *        )
        // *
        // ***********************************************
    }

    public function PasswordReminder(){

        // TODO: possibly not needed so not implenemted yet.
        $res = false;

        return $res;
    }

    public function CandidateApplication($VacancyID, $ContactId) {
        if (empty($VacancyID)) {
            throw new Exception("VacancyID empty");
        }
        if (empty($ContactId)) {
            throw new Exception("ContactId empty");
        }
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'CandidateApplication';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;

        $inputdata = $xml->addChild('InputData');
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'VacancyID');
        $input->addAttribute('Value', $VacancyID);
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'ContactId');
        $input->addAttribute('Value', $ContactId);

        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
        // **********************************************
        // *    Returns
        // *
        // *    [status] => 200
        // *    [response] => SimpleXMLElement Object
        // *    (
        // *        [TimeNow] => 2016-12-05T140600Z
        // *        [ResponseId] => 2380958
        // *        [Status] => Pass
        // *        [Message] => Nothing to say
        // *        [AppliedStatus] => 1
        // *    )
        // *
        // ***********************************************
    }

    public function CandidateLogin($Email, $Password) {
        if (empty($Email)) {
            throw new Exception("Email empty");
        }
        if (empty($Password)) {
            throw new Exception("Password empty");
        }
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'CandidateLogin';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;

        $inputdata = $xml->addChild('InputData');
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'Email');
        $input->addAttribute('Value', $Email);
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'Password');
        $input->addAttribute('Value', $Password);

        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
        // **********************************************
        // *    Returns
        // *
        // *    [status] => 200
        // *    [response] => SimpleXMLElement Object
        // *    (
        // *        [TimeNow] => 2016-12-05T141200Z
        // *        [ResponseId] => 2380996
        // *        [Status] => Pass
        // *        [Message] => Nothing to say
        // *        [ContactId] => 17077800 OR 0
        // *    )
        // *
        // ***********************************************
    }

    public function AttachCV($ContactId, $CVDoc) {
        if (empty($ContactId)) {
            throw new Exception("ContactId empty");
        }
        if (empty($CVDoc) || !file_exists($CVDoc)) {
            throw new Exception("CVDoc empty");
        }

        $path_parts = pathinfo($CVDoc);
        if (empty($path_parts['extension'])) {
            throw new Exception("File extension empty");
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'AttachCV';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;

        $inputdata = $xml->addChild('InputData');
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'ContactId');
        $input->addAttribute('Value', $ContactId);
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'CVDocType');
        $input->addAttribute('Value', $path_parts['extension']);
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'CVBase64');
        $input->addAttribute('Value', base64_encode(file_get_contents($CVDoc)));

        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
        // **********************************************
        // *    Returns
        // *
        // *    [status] => 200
        // *    [response] => SimpleXMLElement Object
        // *    (
        // *        [TimeNow] => 2016-12-05T142700Z
        // *        [ResponseId] => 2381050
        // *        [Status] => Pass
        // *        [Message] => Nothing to say
        // *    )
        // *
        // ***********************************************
    }

    public function CheckAppliedStatus($RequirementId, $CandidateId) {


        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><ChameleonIAPI/>');
        $xml->Method = 'CheckAppliedStatus';
        $xml->APIKey = $this->apikey;
        $xml->UserName = $this->username;

        $inputdata = $xml->addChild('InputData');
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'RequirementId');
        $input->addAttribute('Value', $RequirementId);
        $input = $inputdata->addChild('Input');
        $input->addAttribute('Name', 'CandidateId');
        $input->addAttribute('Value', $CandidateId);

        $res = $this->postxml($xml->asXML());
        $res['response'] = simplexml_load_string($res['response']);

        return $res;
        // **********************************************
        // *    Returns
        // *
        // *    [status] => 200
        // *    [response] => SimpleXMLElement Object
        // *    (
        // *        [TimeNow] => 2016-12-05T143100Z
        // *        [ResponseId] => 2381065
        // *        [Status] => Pass
        // *        [Message] => Nothing to say
        // *        [AppliedStatus] => 0
        // *    )
        // *
        // ***********************************************
    }


}
