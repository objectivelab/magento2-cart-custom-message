<?php

namespace ObjectiveLab\CartCustomMessage\Plugin\Checkout\Controller\Cart;

use Magento\Framework\Message\MessageInterface;

class Add extends \Magento\Checkout\Controller\Cart\Add
{

    /**
     * The purpose of this plugin is to add link to flash message
     * To allow HTML in message (it's escaped by default) we need to change addSuccessMessage() to addSuccess()
     * To avoid overwriting whole execute() method for that I used this hack
     */
    public function afterExecute()
    {
        $lastAddedMessage = $this->messageManager->getMessages()->getLastAddedMessage();
        if ($lastAddedMessage && $lastAddedMessage->getType() == MessageInterface::TYPE_SUCCESS) {
            $text = $lastAddedMessage->getText();

            /**
             * @var \Magento\Framework\Message\Collection
             */
            $messages = $this->messageManager->getMessages();
            $messages->deleteMessageByIdentifier($lastAddedMessage->getIdentifier());

	    // message text with HTML can be added to translation file of your theme now
	    // "You added %1 to your shopping cart.","You added %1 to your shopping cart. <a href='/checkout/cart/'>View cart</a>" 
            $this->messageManager->addSuccess($text);
        }

        return $this->goBack();
    }

}
