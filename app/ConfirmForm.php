<?php

use Nette\Forms\Form;

/**
 * @author Vojtěch Kohout
 */
class ConfirmForm
{

	/**
	 * @return Form
	 */
	public function createCreateForm()
	{
		$form = new Form;
		$form->addSubmit('ok', 'Ano');
		$form->addSubmit('cancel', 'Ne');
		return $form;
	}

}
