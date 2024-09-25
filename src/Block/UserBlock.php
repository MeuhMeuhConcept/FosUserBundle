<?php

namespace MMC\FosUserBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Validator\ErrorElement as ValidatorErrorElement;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class UserBlock extends AbstractBlockService
{
    protected string $name;

    public function __construct(
        Environment $engine,
        string $name
    ) {
        parent::__construct($engine);

        $this->name = $name;
    }

    public function validateBlock(ValidatorErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        return $this->renderResponse('MMCFosUserBundle:Admin:admin-user-layout.html.twig', [
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ], $response);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
