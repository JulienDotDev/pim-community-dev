<?php

namespace Pim\Component\Catalog\Localization;

use Akeneo\Component\Localization\Model\TranslatableInterface;
use Akeneo\Component\Localization\TranslatableUpdater;
use Akeneo\Component\StorageUtils\Updater\ObjectUpdaterInterface;

/**
 * Decorates category updater to translate labels.
 *
 * @author    Marie Bochu <marie.bochu@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ChannelUpdater implements ObjectUpdaterInterface
{
    /** @var ObjectUpdaterInterface */
    protected $channelUpdater;

    /** @var TranslatableUpdater */
    protected $translatableUpdater;

    /**
     * @param ObjectUpdaterInterface $channelUpdater
     * @param TranslatableUpdater    $translatableUpdater
     */
    public function __construct(ObjectUpdaterInterface $channelUpdater, TranslatableUpdater $translatableUpdater)
    {
        $this->channelUpdater = $channelUpdater;
        $this->translatableUpdater = $translatableUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public function update($channel, array $data, array $options = [])
    {
        try {
            $this->channelUpdater->update($channel, $data, $options);

            if (isset($data['labels']) && $channel instanceof TranslatableInterface) {
                $this->translatableUpdater->update($channel, $data['labels']);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $this;
    }
}
