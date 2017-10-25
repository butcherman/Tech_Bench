<?php
/*
|   VcardDownload class uses the Jeroendesloovere VCard class to generate a downloadable VCard
|   for users to download and install in their Outlook or phone
*/

use JeroenDesloovere\VCard\VCard;

class VcardDownload extends Controller
{
    public static function getVcard($contID)
    {
        $obj = new self;
        $model = $obj->model('customers');
        
        //  Get the contact information
        $contactData = $model->getOneContact($contID);
        $contactPhone = $model->getContactPhone($contID);
        $custData = $model->getCustData($contactData->cust_id);
        
        $contactName = explode(' ', $contactData->name);
        $firstName = $contactName[0];
        $lastName = isset($contactName[1]) ? $contactName[1] : '';
        $additional = '';
        $prefix = '';
        $suffix = '';
        
        //  Create the VCard object
        $vcard = new VCard();
        
        //  Add personal data to VCard
        $vcard->addName($lastName, $firstName, $additional, $prefix, $suffix);
        
        //  Add Company Data to VCard
        $vcard->addCompany($custData->name);
        $vcard->addEmail($contactData->email);
        $vcard->addAddress(null, null, $custData->address, $custData->city, $custData->state, $custData->zip, null);
        
        //  Add phone numbers to VCard
        if(!empty($contactPhone))
        {
            foreach($contactPhone as $phone)
            {
                $vcard->addPhoneNumber($phone->phone_number, $phone->description);
            }
        }
        
        //  Return th eVCard
        return $vcard->download();
    }
}
