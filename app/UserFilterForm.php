<?php

use Nette\Forms\Form;

/**
 * @author Vojtěch Kohout
 */
class UserFilterForm
{

	/**
	 * @return Form
	 */
	public function createForm()
	{
		$form = new Form;

		$form->addText('keyword', 'Klíčové slovo:');
		$form->addSubmit('submit', 'Filtrovat');

		return $form;
	}

}
