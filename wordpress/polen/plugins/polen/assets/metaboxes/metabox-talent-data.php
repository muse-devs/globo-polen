<?php if( isset( $_REQUEST['user_id'] ) ) { } ?>
<div id="metaboxSellerData">
    <h2>Dados do Talento</h2>
    <?php
    $current_screen = get_current_screen();
    $Polen_Update_Fields = new Polen_Update_Fields();
    $user = get_user_by( 'id', $_REQUEST['user_id'] );
    if( ( isset( $user->caps['user_talent'] ) && $user->caps['user_talent'] == true ) ) {
        $UserInfo = $Polen_Update_Fields->get_vendor_data( intval( $_REQUEST['user_id'] ) );
    } elseif( ( isset( $user->caps['user_charity'] ) && $user->caps['user_charity'] == true ) ) {
        $UserInfo = $Polen_Update_Fields->get_charity_data( intval( $_REQUEST['user_id'] ) );
    }
    ?>
    <table class="form-table">
        <tbody>
            <td>
                <div id="PolenVendorTabs">
                    <ul>
                        <li><a href="#PolenVendorProfileTab0">Geral</a></li>
                        <li><a href="#PolenVendorProfileTab1">Dados do Talento</a></li>
                        <li><a href="#PolenVendorProfileTab2">Informações de Contato</a></li>
                        <li><a href="#PolenVendorProfileTab3">Redes Sociais</a></li>
                        <?php if( is_admin() && ( $current_screen->base == 'user-edit' || ( $current_screen->base == 'user' && $current_screen->action == 'add' ) ) ) { ?>
                        <li><a href="#PolenVendorProfileTab5">Dados Bancários</a></li>
                        <li><a href="#PolenVendorProfileTab6">Configurações Financeiras</a></li>
                        <li><a href="#PolenVendorProfileTab7">Doações</a></li>
                        <?php } ?>
                    </ul>

                    <!-- Dados da Loja -->
                    <div id="PolenVendorProfileTab0">
                        <table class="form-table">
                            <tr>
                                <th>
                                    Slug do Perfil
                                </th>
                                <td>
                                    <input type="text" name="talent_alias" value="<?php echo ( isset( $UserInfo->talent_alias ) ) ? $UserInfo->talent_alias : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                    <small>Ex: <?php bloginfo( 'url' ); ?><strong>/nome-do-talento/</strong></small>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Responde em
                                </th>
                                <td>
                                    <input type="text" name="tempo_resposta" value="<?php echo ( isset( $UserInfo->tempo_resposta ) ) ? $UserInfo->tempo_resposta : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Profissão
                                </th>
                                <td>
                                    <input type="text" name="profissao" value="<?php echo ( isset( $UserInfo->profissao ) ) ? $UserInfo->profissao : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Descrição
                                </th>
                                <td>
                                    <textarea name="descricao" rows="5" cols="80" class="widefat" style="width: 100%;"><?php echo ( isset( $UserInfo->descricao ) ) ? $UserInfo->descricao : ''; ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Dados do Talento -->
                    <div id="PolenVendorProfileTab1">
                        <table class="form-table">
                            <tr>
                                <th>
                                    Categorias do Talento
                                </th>
                                <td>
                                    <select name="talent_category[]" id="talent_category" class="widefat"  multiple="multiple">
                                        <option value=""><?php echo esc_html__('Choose a talent category', 'polen' );?></option>
                                        <?php 
                                        $terms = get_terms( array(
                                            'taxonomy' => 'talent_category',
                                            'hide_empty' => false,
                                        ) );
                                        if( ! is_wp_error( $terms ) && count( $terms ) > 0 ) {
                                            $arrSelected = array();
                                            $storeCategories = wp_get_object_terms( $_REQUEST['user_id'], 'talent_category' );
                                            foreach( $storeCategories as $talentCategory ){
                                                $arrSelected[] = $talentCategory->slug;
                                            }

                                            foreach( $terms as $term) {
                                        ?> 
                                                <option value="<?php echo $term->slug; ?>" <?php echo selected( in_array( $term->slug, $arrSelected )); ?> >
                                                    <?php echo $term->name; ?>
                                                </option> 
                                        <?php } } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Natureza Jurídica
                                </th>
                                <td>
                                    <select name="natureza_juridica" id="polen_natureza_juridica" class="widefat">
                                        <option value="PJ" <?php echo ( isset( $UserInfo->natureza_juridica ) && $UserInfo->natureza_juridica == 'PJ' ) ? ' selected' : ''; ?>>Pessoa Jurídica</option>
                                        <option value="PF" <?php echo ( isset( $UserInfo->natureza_juridica ) && $UserInfo->natureza_juridica == 'PF' ) ? ' selected' : ''; ?>>Pessoa Física</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="natureza-juridica-pj">
                                <th>
                                    Razão Social
                                </th>
                                <td>
                                    <input type="text" name="razao_social" value="<?php echo ( isset( $UserInfo->razao_social ) ) ? $UserInfo->razao_social : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                </td>
                            </tr>
                            <tr class="natureza-juridica-pj">
                                <th>
                                    Nome Fantasia
                                </th>
                                <td>
                                    <input type="text" name="nome_fantasia" value="<?php echo ( isset( $UserInfo->nome_fantasia ) ) ? $UserInfo->nome_fantasia : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                </td>
                            </tr>
                            <tr class="natureza-juridica-pj">
                                <th>
                                    CNPJ
                                </th>
                                <td>
                                    <input type="text" name="cnpj" value="<?php echo ( isset( $UserInfo->cnpj ) ) ? $UserInfo->cnpj : ''; ?>" autocomplete="password" class="widefat polen-cnpj" maxlength="18">
                                </td>
                            </tr>
                            <tr class="natureza-juridica-pj">
                                <th>
                                    Reter ISS na Fonte?
                                </th>
                                <td>
                                    <input type="radio" name="reter_iss" value="S"<?php echo ( isset( $UserInfo->reter_iss ) && $UserInfo->reter_iss == 'S' ) ? ' checked="checked"' : ''; ?>> Sim
                                    &nbsp;
                                    <input type="radio" name="reter_iss" value="N"<?php echo ( ( isset( $UserInfo->reter_iss ) && $UserInfo->reter_iss == 'N' ) || ( ! isset( $UserInfo->reter_iss ) || is_null( $UserInfo->reter_iss ) || $UserInfo->reter_iss != 'S' ) ) ? ' checked="checked"' : ''; ?>> Não
                                </td>
                            </tr>
                            <tr class="natureza-juridica-pf">
                                <th>
                                    Nome
                                </th>
                                <td>
                                    <input type="text" name="nome" value="<?php echo ( isset( $UserInfo->nome ) ) ? $UserInfo->nome : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                </td>
                            </tr>
                            <tr class="natureza-juridica-pf">
                                <th>
                                    CPF
                                </th>
                                <td>
                                    <input type="text" name="cpf" value="<?php echo ( isset( $UserInfo->cpf ) ) ? $UserInfo->cpf : ''; ?>" autocomplete="password" class="widefat polen-cpf" maxlength="14">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Informações de Contato -->
                    <div id="PolenVendorProfileTab2">
                        <table class="form-table">
                            <tr>
                                <th>
                                    E-mail
                                </th>
                                <td>
                                    <input type="text" id="store_email" name="store_email" value="<?php echo ( isset( $UserInfo->email ) ) ? $UserInfo->email : ''; ?>" autocomplete="password" class="widefat" maxlength="255">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Telefone
                                </th>
                                <td>
                                    <input type="text" name="telefone" value="<?php echo ( isset( $UserInfo->telefone ) ) ? $UserInfo->telefone : ''; ?>" autocomplete="password" class="widefat polen-phone" maxlength="15">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Celular
                                </th>
                                <td>
                                    <input type="text" name="celular" value="<?php echo ( isset( $UserInfo->celular ) ) ? $UserInfo->celular : ''; ?>" autocomplete="password" class="widefat polen-phone" maxlength="15">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    WhatsApp
                                </th>
                                <td>
                                    <input type="text" name="whatsapp" value="<?php echo ( isset( $UserInfo->whatsapp ) ) ? $UserInfo->whatsapp : ''; ?>" autocomplete="password" class="widefat polen-phone" maxlength="15">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Redes Sociais -->
                    <div id="PolenVendorProfileTab3">
                        <table class="form-table">
                            <tr>
                                <th>
                                    Facebook
                                </th>
                                <td>
                                    <input type="text" name="facebook" value="<?php echo ( isset( $UserInfo->facebook ) ) ? $UserInfo->facebook : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Instagram
                                </th>
                                <td>
                                    <input type="text" name="instagram" value="<?php echo ( isset( $UserInfo->instagram ) ) ? $UserInfo->instagram : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Twitter
                                </th>
                                <td>
                                    <input type="text" name="twitter" value="<?php echo ( isset( $UserInfo->twitter ) ) ? $UserInfo->twitter : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Pinterest
                                </th>
                                <td>
                                    <input type="text" name="pinterest" value="<?php echo ( isset( $UserInfo->pinterest ) ) ? $UserInfo->pinterest : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Linked In
                                </th>
                                <td>
                                    <input type="text" name="linkedin" value="<?php echo ( isset( $UserInfo->linkedin ) ) ? $UserInfo->linkedin : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    YouTube
                                </th>
                                <td>
                                    <input type="text" name="youtube" value="<?php echo ( isset( $UserInfo->youtube ) ) ? $UserInfo->youtube : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <?php if( is_admin() && ( $current_screen->base == 'user-edit' || ( $current_screen->base == 'user' && $current_screen->action == 'add' ) ) ) { ?>
                    <!-- Dados Bancários -->
                    <div id="PolenVendorProfileTab5">
                    <table class="form-table">
                            <tr>
                                <th>
                                    Banco
                                </th>
                                <td>
                                    <?php
                                    $Polen_Bancos = new Polen_Bancos();
                                    $bancos = $Polen_Bancos->listar();
                                    ?>
                                    <select id="polen_banco" name="banco" class="widefat">
                                        <option></option>
                                        <?php
                                        if( $bancos && is_array( $bancos ) && count( $bancos ) > 0 ) {
                                            foreach( $bancos as $k => $banco ) {
                                                $codigo_banco = str_pad( $banco->codigo, 3, "0", STR_PAD_LEFT );
                                                $selected = ( isset( $UserInfo->codigo_banco ) && strval( $UserInfo->codigo_banco ) == strval( $codigo_banco ) ) ? ' selected' : '';
                                        ?>
                                        <option <?php echo $selected; ?> value="<?php echo $codigo_banco; ?>:<?php echo $banco->nome; ?>"><?php echo $codigo_banco . ' - ' . $banco->nome; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Agência
                                </th>
                                <td>
                                    <input type="text" name="agencia" value="<?php echo ( isset( $UserInfo->agencia ) ) ? $UserInfo->agencia : ''; ?>" autocomplete="password" class="widefat polen-digits-only" maxlength="4">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Conta
                                </th>
                                <td>
                                    <input type="text" name="conta" value="<?php echo ( isset( $UserInfo->conta ) ) ? $UserInfo->conta : ''; ?>" autocomplete="password" class="widefat polen-bank-account" maxlength="20">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Tipo de Conta
                                </th>
                                <td>
                                    <select name="tipo_conta" class="widefat">
                                        <option value="">Selecione...</option>
                                        <option value="CC" <?php echo ( isset( $UserInfo->tipo_conta ) && $UserInfo->tipo_conta == 'CC' ) ? ' selected' : ''; ?>>Conta Corrente</option>
                                        <option value="CP" <?php echo ( isset( $UserInfo->tipo_conta ) && $UserInfo->tipo_conta == 'CP' ) ? ' selected' : ''; ?>>Conta Poupança</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Configurações -->
                    <div id="PolenVendorProfileTab6">
                        <table class="form-table">
                            <tr>
                                <th>
                                    Subordinate Merchant ID
                                </th>
                                <td>
                                    <input type="text" name="subordinate_merchant_id" value="<?php echo ( isset( $UserInfo->subordinate_merchant_id ) ) ? $UserInfo->subordinate_merchant_id : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    MDR
                                </th>
                                <td>
                                    <input type="text" name="mdr" value="<?php echo ( isset( $UserInfo->mdr ) ) ? $UserInfo->mdr : ''; ?>" autocomplete="password" class="widefat">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Fee
                                </th>
                                <td>
                                    <input type="text" name="fee" value="<?php echo ( isset( $UserInfo->fee ) ) ? $UserInfo->fee : ''; ?>" autocomplete="password" class="widefat polen-digits-only" maxlength="5">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Doações -->
                    <div id="PolenVendorProfileTab7">
                        <table class="form-table">
                            <tr>
                                <td>
                                    <input type="checkbox" name="charity_enable" id="charity_enable" value="yes" <?php echo ( isset( $UserInfo->charity_enable ) && strval( $UserInfo->charity_enable ) == strval('1') ) ? 'checked' : ''; ?>> Doar cachê para instituição de caridade
                                </td>
                            </tr>
                            <tr id="tr_charity_to">
                                <td>
                                    <select name="charity_to" id="charity_to" class="widefat">
                                        <option></option>
                                        <?php
                                        $args = array(
                                            'role__in' => 'user_charity',
                                            'orderby'  => 'display_name',
                                            'order'    => 'ASC',
                                        );
                                        $charity_users = get_users( $args );
                                        if( $charity_users && ! is_null( $charity_users ) && is_array( $charity_users ) && ! empty( $charity_users ) ) {
                                            foreach( $charity_users as $k => $polen_user ) {
                                                $selected = ( $UserInfo->charity_to == $polen_user->ID ) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $polen_user->ID; ?>" <?php echo $selected; ?>><?php echo $polen_user->display_name; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </td>
        </tbody>
    </table>
</div>