<?php

namespace App\Command;

use App\Service\CombineService;
use App\Service\SplitStringService;
use App\Service\SubsetProblemSolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ComputeMultiplicityCommand
 *
 * @package App\Command
 */
class ComputeMultiplicityCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:compute-multiplicity';

    /**
     * @var CombineService
     */
    protected $combineService;

    /**
     * @var SubsetProblemSolver
     */
    protected $subsetProblemSolver;

    /**
     * @var SplitStringService
     */
    protected $splitStringService;

    /**
     * ComputeMultiplicityCommand constructor.
     *
     * @param CombineService $combineService
     * @param SubsetProblemSolver $subsetProblemSolver
     * @param SplitStringService $splitStringService
     */
    public function __construct(CombineService $combineService, SubsetProblemSolver $subsetProblemSolver, SplitStringService $splitStringService)
    {
        $this->combineService = $combineService;
        $this->subsetProblemSolver = $subsetProblemSolver;

        parent::__construct();
        $this->splitStringService = $splitStringService;
    }

    protected function configure()
    {
        $this->addArgument('string', InputArgument::REQUIRED, 'Source string');
        $this->addArgument('max_length', InputArgument::REQUIRED, 'Max length of combination');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceString = $input->getArgument('string');

        $maxLength = $input->getArgument('max_length');

        $output->writeln([
            'Source string',
            '=========================',
        ]);

        $output->writeln($sourceString);

        $output->writeln('');

        $output->writeln([
            'Max length of combination',
            '=========================',
        ]);

        $output->writeln($maxLength);
        $output->writeln('=========================');

        $stringLength = strlen($sourceString);

        if ($stringLength < $maxLength) {
            $output->writeln([
                'Max length can`t be less then combination',
                '=========================',
            ]);
            exit();
        }

        if ($stringLength == $maxLength) {
            $output->writeln([
                sprintf('Posible only variant: %s', $sourceString),
                '=========================',
            ]);
            exit();
        }

        $arrayOfPosition = range($maxLength, $stringLength);

        array_pop($arrayOfPosition);

        $this->subsetProblemSolver->setSum($stringLength);

        $this->subsetProblemSolver->setArray($arrayOfPosition);

        $variants = $this->subsetProblemSolver->solve();
        $elements = [];
        if (!empty($variants)) {
            foreach ($variants as $variant) {
                $elements[] = $this->combineService->getCombination($variant);
            }
        }

        if (!empty($elements)) {
            $result = array_merge(...$elements);
        } else {
            $result = [];
        }

        $a = [];

        foreach ($result as $element) {
            $a[] = $this->splitStringService->getSplitedString($sourceString, $element);
        }

        $combinations = $this->splitStringService->getSplitLength($sourceString, $maxLength);

        foreach ($combinations as $lenghth) {
            $a[] = str_split($sourceString, $lenghth);
        }

        array_walk($a, function (&$name) {
            $name = implode('-', $name);
        });

        $output->writeln($a);

        $output->writeln([
            '=========================',
            'End',
        ]);
    }
}