<?php
class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("registrocorrispettivi_form", array("legend"=>Mage::helper("registrocorrispettivi")->__("Informazioni Dettaglio")));

        $fieldset->addField("increment_id", "text", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Id Ordine"),
            "class" => "required-entry",
            "required" => true,
            "name" => "increment_id",
        ));
        $fieldset->addField("tipo", "select", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Tipo"),
            "class" => "required-entry",
            "required" => true,
            "name" => "tipo",
            "values" => Mage::getSingleton('registrocorrispettivi/tipodocumento')->getAsOption()
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('data_comp', 'date', array(
            'name'         => 'data_comp',
            'label'        => Mage::helper('registrocorrispettivi')->__('Data'),
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'=>true
        ));
        $fieldset->addField("imponibile", "text", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Imponibile"),
            "class" => "required-entry",
            "required" => true,
            "name" => "imponibile",
        ));


        $fieldset->addField("iva", "text", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Tassa"),
            "class" => "required-entry",
            "required" => true,
            "name" => "iva",
        ));

        $fieldset->addField("aliquota", "text", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Aliquota"),
            "class" => "required-entry",
            "required" => true,
            "name" => "aliquota",
        ));

        $fieldset->addField("discount_amount", "text", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Sconto"),
            "class" => "required-entry",
            "required" => true,
            "name" => "discount_amount",
        ));

        $fieldset->addField("note", "textarea", array(
            "label" => Mage::helper("registrocorrispettivi")->__("Note"),
            "class" => "required-entry",
            "required" => true,
            "name" => "note",
        ));


        if (Mage::getSingleton("adminhtml/session")->getRegistrocorrispettiviData())
        {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getRegistrocorrispettiviData());
            Mage::getSingleton("adminhtml/session")->setRegistrocorrispettiviData(null);
        }
        elseif(Mage::registry("registrocorrispettivi_data")) {
            $form->setValues(Mage::registry("registrocorrispettivi_data")->getData());
        }
        return parent::_prepareForm();
    }
}
