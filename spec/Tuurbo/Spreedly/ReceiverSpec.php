<?php

namespace spec\Tuurbo\Spreedly;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tuurbo\Spreedly\Client;

class ReceiverSpec extends ObjectBehavior
{
    const RECEIVER_TOKEN = '...RECEIVER_TOKEN...';

    public function let(Client $client)
    {
        $this->beConstructedWith($client, [], self::RECEIVER_TOKEN);

        $this->shouldHaveType('Tuurbo\Spreedly\Receiver');
    }

    public function it_gets_a_list_of_all_receivers($client)
    {
        $client->get('v1/receivers.json?since_token='.self::RECEIVER_TOKEN)
            ->shouldBeCalled()
            ->willReturn($client);

        $this->all(self::RECEIVER_TOKEN)->shouldReturnAnInstanceOf('Tuurbo\Spreedly\Client');
    }

    public function it_creates_a_receiver($client)
    {
        $data = [
            'receiver_type' => 'test',
            'hostnames' => 'http://example.com',
            'credentials' => [
                [
                    'name' => 'app-1',
                    'value' => '1234',
                ]
            ],
        ];

        $client->post('v1/receivers.json', ['receiver' => $data])
            ->shouldBeCalled()
            ->willReturn($client);

        $this->create('test', ['app-1' => '1234'], 'http://example.com')->shouldReturnAnInstanceOf('Tuurbo\Spreedly\Client');
    }

    public function it_updates_a_receiver($client)
    {
        $data = [
            'credentials' => [
                [
                    'name' => 'app-1',
                    'value' => '1234',
                ],
                [
                    'name' => 'app-2',
                    'value' => '4567',
                ]
            ],
        ];

        $client->put('v1/receivers/'.self::RECEIVER_TOKEN.'.json', ['receiver' => $data])
            ->shouldBeCalled()
            ->willReturn($client);

        $this->update(['app-1' => '1234', 'app-2' => '4567'])->shouldReturnAnInstanceOf('Tuurbo\Spreedly\Client');
    }

    public function it_gets_a_single_receiver($client)
    {
        $client->get('v1/receivers/'.self::RECEIVER_TOKEN.'.json')
            ->shouldBeCalled()
            ->willReturn($client);

        $this->get()->shouldReturnAnInstanceOf('Tuurbo\Spreedly\Client');
    }

    public function it_delivers_a_payment_method($client)
    {
        $paymentToken = 'abcdefghijk';

        $data = [
            'payment_method_token' => $paymentToken,
            'url' => 'http://example.com/post',
            'headers' => 'Header-1: value-1/nHeader-2: value-2',
        ];

        $client->post('v1/receivers/'.self::RECEIVER_TOKEN.'/deliver.json', ['delivery' => $data])
            ->shouldBeCalled()
            ->willReturn($client);

        $headers = ['Header-1' => 'value-1', 'Header-2' => 'value-2'];

        $this->deliver($paymentToken, 'http://example.com/post', $headers, "")->shouldReturnAnInstanceOf('Tuurbo\Spreedly\Client');
    }
}
