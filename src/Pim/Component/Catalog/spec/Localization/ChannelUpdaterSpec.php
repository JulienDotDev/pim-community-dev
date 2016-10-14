<?php

namespace spec\Pim\Component\Catalog\Localization;

use Akeneo\Component\Localization\TranslatableUpdater;
use Akeneo\Component\StorageUtils\Updater\ObjectUpdaterInterface;
use PhpSpec\ObjectBehavior;
use Pim\Component\Catalog\Model\ChannelInterface;
use Pim\Component\Catalog\Model\ProductInterface;
use Prophecy\Argument;

class ChannelUpdaterSpec extends ObjectBehavior
{
    function let(ObjectUpdaterInterface $channelUpdater, TranslatableUpdater $translatableUpdater)
    {
        $this->beConstructedWith($channelUpdater, $translatableUpdater);
    }

    function it_updates_and_translates_channel_labels(
        $channelUpdater,
        $translatableUpdater,
        ChannelInterface $channel
    ) {
        $data = [
            'labels' => [
                'en_US' => 'My category'
            ]
        ];
        $options = [];

        $channelUpdater->update($channel, $data, $options)->shouldBeCalled();
        $translatableUpdater->update($channel, $data['labels'])->shouldBeCalled();

        $this->update($channel, $data, $options);
    }

    function it_does_not_translate_inexistant_labels(
        $channelUpdater,
        $translatableUpdater,
        ChannelInterface $channel
    ) {
        $data = [
            'data' => [
                'en_US' => 'My category'
            ]
        ];
        $options = [];

        $channelUpdater->update($channel, $data, $options)->shouldBeCalled();
        $translatableUpdater->update($channel, Argument::any())->shouldNotBeCalled();

        $this->update($channel, $data, $options);
    }

    function it_does_not_translate_if_object_is_not_a_translatable_interface(
        $channelUpdater,
        $translatableUpdater,
        ProductInterface $product
    ) {
        $data = [
            'data' => [
                'en_US' => 'My category'
            ]
        ];
        $options = [];

        $channelUpdater->update($product, $data, $options)->shouldBeCalled();
        $translatableUpdater->update($product, Argument::any())->shouldNotBeCalled();

        $this->update($product, $data, $options);
    }

    function it_throws_an_exception_if_updater_has_thrown_exception(
        $channelUpdater,
        $translatableUpdater,
        ChannelInterface $channel
    ) {
        $data = [
            'data' => [
                'en_US' => 'My category'
            ]
        ];
        $options = [];

        $channelUpdater->update($channel, $data, $options)->willThrow(new \InvalidArgumentException());
        $translatableUpdater->update($channel, Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(new \InvalidArgumentException())->during('update', [$channel, $data, $options]);
    }
}
