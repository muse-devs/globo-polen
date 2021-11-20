<?php
namespace Polen\Includes\Orders;

interface Polen_IOrder
{
    /**
     * Emails Fan
     */
    //TODO: emails do fan
    //TODO: emails do fan - pedido recebido
    //TODO: emails do fan - pagamento aprovado
    //TODO: emails do fan - talento aceitou
    //TODO: emails do fan - talento negou
    //TODO: emails do fan - pedido expirou
    //TODO: emails do fan - pedido completo

    //TODO: emails talento
    //TODO: emails do talento - pagamento aprovado
    //TODO: emails do talento - pagamento aprovado logo
    //TODO: emails do talento - pagamento aprovado get path tamplate
    //TODO: emails do talento - pagamento aprovado handler para o envio
    //TODO: emails do talento - resumo de pagamento

    public function is_polen_payed();
    public function is_cupom_polen();

    public function get_price_talent();
    public function get_price_talent_html();
    public function get_price_fan();
    public function get_price_fan_html();
    
    public function get_fan_price();
    public function get_fan_deadline();

}
