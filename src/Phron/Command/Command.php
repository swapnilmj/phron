<?php namespace Phron\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\TableHelper;
use Phron\View\TaskTableView;

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
     * Display a message on the screen
     * 
     * @param string $message
     * @return string
     */
    public function writeln($message)
    {
        return $this->output->writeln('<comment>' . $message . '</comment>');
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
                    ->ask(
                        $this->input, 
                        $this->output, 
                        new Question($question)
                    );
    }
    
    /**
     * Ask the user the given question and validate it
     * 
     * @param string $question
     * @param callable $validationHandler
     */
    public function askAndValidate($question, callable $validationHandler)
    {
        $dialog   = $this->getHelper("dialog");
        $question = '<comment>'.$question.'</comment> ';

        return $dialog->askAndValidate($this->output, $question, $validationHandler, false);
    }
    
    /**
     * Ask the user the qiven and give him options to select from
     * 
     * @param string $question
     * @param array $options
     * @return string
     */
    public function askWithOptions($question, $options)
    {
        $dialog   = $this->getHelper("dialog");
        $question = '<info>'.$question.'</info> ';
        
        return $dialog->select($this->output, $question, $options, 0);
    }

    /**
     * Ask the user to confirm an action
     *
     * @param string $question
     * @return string
     */
    public function confirm($question)
    {
        $formattedQuestion = "<question>$question [y/n]</question>: ";
        $dialog   = $this->getHelper("question");
        $question = new ConfirmationQuestion($formattedQuestion, false);

        return $dialog->ask($this->input, $this->output, $question);
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

    /**
     * Displays all cronjobs in a table.
     *
     * @param array $jobs
     * @return string
     */
    public function displayTasks(TableHelper $table, array $jobs)
    {
        $headers = array('Id', 'Expression', 'Command', 'Comments', 'Log File', 'Error Log');
        if (empty($jobs))
        {
            $this->writeln("Crontab is empty.");
        }
        else
        {
            TaskTableView::render($table, $this->output, $headers, $jobs);
        }
    }
}
