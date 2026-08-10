<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormG1145Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%G-1145%')->orWhere('subtitle', 'like', '%G-1145%')->first();
        if (!$service) {
            echo "Service G-1145 not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'g-1145'],
            ['name' => 'e-Notification of Application/Petition Acceptance', 'description' => 'Form G-1145']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        $sec1 = $form->sections()->create(['title' => 'Form G-1145', 'order' => 1]);
        
        $this->addQ($sec1, 'What Is the Purpose of This Form?', 'headingPurpose', 'heading');
        $this->addQ($sec1, 'Use this form to request an electronic notification (e-Notification) when U.S. Citizenship and Immigration Services accepts your immigration application. This service is available for applications filed at a USCIS Lockbox facility.', 'headingPurposeNote', 'heading');
        
        $this->addQ($sec1, 'General Information', 'headingGeneralInfo', 'heading');
        $this->addQ($sec1, 'Complete the information below and clip this form to the first page of your application package. You will receive one e-mail and/or text message for each form you are filing. We will send the e-Notification within 24 hours after we accept your application. Domestic customers will receive an e-mail and/or text message; overseas customers will only receive an e-mail. Undeliverable e-Notifications cannot be resent.', 'headingGeneralInfoNote1', 'heading');
        $this->addQ($sec1, 'The e-mail or text message will display your receipt number and tell you how to get updated case status information. It will not include any personal information. The e-Notification does not grant any type of status or benefit; rather it is provided as a convenience to customers.', 'headingGeneralInfoNote2', 'heading');
        $this->addQ($sec1, 'USCIS will also mail you a receipt notice (I-797C), which you will receive within 10 days after your application has been accepted; use this notice as proof of your pending application or petition.', 'headingGeneralInfoNote3', 'heading');
        
        $this->addQ($sec1, 'USCIS Privacy Act Statement', 'headingPrivacy', 'heading');
        $this->addQ($sec1, 'AUTHORITIES: The information requested on this form is collected pursuant to section 103(a) of the Immigration and Nationality Act, as amended INA section 101, et seq. PURPOSE: The primary purpose for providing the information on this form is to request an electronic notification when USCIS accepts immigration form. The information you provide will be used to send you a text and/or email message. DISCLOSURE: The information you provide is voluntary. However, failure to provide the requested information may prevent USCIS from providing you a text and/or email message receipting your immigration form. ROUTINE USES: The information provided on this form will be used by and disclosed to DHS personnel and contractors in accordance with approved routine uses...', 'headingPrivacyNote', 'heading');

        $this->addQ($sec1, 'Complete this form and clip it on top of the first page of your immigration form(s).', 'headingCompleteNote', 'heading');
        $this->addQ($sec1, 'Applicant/Petitioner Full Last Name', 'lastName');
        $this->addQ($sec1, 'Applicant/Petitioner Full First Name', 'firstName');
        $this->addQ($sec1, 'Applicant/Petitioner Full Middle Name', 'middleName');
        $this->addQ($sec1, 'Email Address', 'emailAddress');
        $this->addQ($sec1, 'Mobile Phone Number (Text Message)', 'mobilePhone');

        echo "Successfully seeded G-1145 form!\n";
    }

    private function addQ($section, $text, $name, $type = 'text', $options = [], $required = false) {
        static $order = 1;
        $q = $section->questions()->create([
            'question_text' => mb_substr($text, 0, 255),
            'field_name' => $name,
            'field_type' => $type,
            'is_required' => $required,
            'order' => $order++
        ]);
        foreach ($options as $idx => $opt) {
            $q->options()->create([
                'option_label' => mb_substr($opt, 0, 150),
                'option_value' => mb_substr(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $opt)), 0, 150),
                'order' => $idx + 1
            ]);
        }
    }
}