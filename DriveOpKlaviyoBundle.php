<?php
namespace DriveOp\KlaviyoBundle;

use DriveOp\KlaviyoBundle\DependencyInjection\DriveOpKlaviyoExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DriveOpKlaviyoBundle extends Bundle
{

    /**
     * {@inheritDoc}
     * @version 0.0.1
     * @since 0.0.1
     */
    public function getContainerExtension()
    {
        // this allows us to have custom extension alias
        // default convention would put a lot of underscores
        if (null === $this->extension) {
            $this->extension = new DriveOpKlaviyoExtension();
        }

        return $this->extension;
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        // TODO: Implement getParent() method.
    }
}