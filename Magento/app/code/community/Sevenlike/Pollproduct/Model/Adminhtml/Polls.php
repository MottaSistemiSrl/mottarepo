<?php

class Sevenlike_Pollproduct_Model_Adminhtml_Polls extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        $return = array();
        $return[] = array(
            "label" => "",
            "value" => null
        );

        $polls = Mage::getModel("poll/poll")->getCollection();
        foreach ($polls as $poll) {
            $return[] = array(
                "label" => $poll->getPollTitle(),
                "value" => $poll->getId()
            );
        }

        return $return;
    }
}