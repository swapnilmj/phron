<?php namespace Phron\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

trait Command {

	/**
	 * Execute the command.
	 *
	 * @param  \Symfony\Component\Console\Input\InputInterface  $input
	 * @param  \Symfony\Component\Console\Output\OutputInterface  $output
	 * @return void
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;

		return $this->fire();
	}

	/**
	 * Get an argument from the input.
	 *
	 * @param  string  $key
	 * @return string
	 */
	public function argument($key)
	{
		return $this->input->getArgument($key);
	}

	/**
	 * Get an option from the input.
	 *
	 * @param  string  $key
	 * @return string
	 */
	public function option($key)
	{
		return $this->input->getOption($key);
	}

	/**
	 * Ask the user the given question.
	 *
	 * @param  string  $question
	 * @return string
	 */
	public function ask($question)
	{
		$question = '<comment>'.$question.'</comment> ';
        
		return $this->getHelperSet()
		            ->get('question')
		            ->ask($this->input, $this->output, new Question($question));
	}

	/**
	 * Ask the user the given secret question.
	 *
	 * @param  string  $question
	 * @return string
	 */
	public function secret($question)
	{
		$question = '<comment>'.$question.'</comment> ';

		return $this->getHelperSet()->get('dialog')->askHiddenResponse($this->output, $question, false);
	}

}