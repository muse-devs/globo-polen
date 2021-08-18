<?php

class Products_Rest {

    /**
     * ID do plugin
     */
    private $plugin_name;

    /**
     * Versão do plugin
     */
    private $version;

    /**
     * Inicializar a classe
     *
     * @param String $plugin_name
     * @param String $version
     */
    public function __construct(string $plugin_name, string $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Listar posts de produto
     *
     * @param object $request
     * @return WP_REST_Response
     */
    public function list(object $request): WP_REST_Response
    {
        try{
            $perPage = $request->get_param('perPage') ? $request->get_param('perPage') : get_option('posts_per_page');
            $paged = $request->get_param('paged') ? $request->get_param('paged') : 1;

            $args = array(
                'post_type' => 'post_product',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => $perPage,
                'paged' => $paged,
            );

            $items = array();
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $items[] = $this->posts_response(get_the_ID());
                }
            }

            $data = array(
                'items' => $items,
                'total' => (int) $query->found_posts,
                'currentPage' => $paged,
                'perPage' => count($items),
            );

            return $this->api_response($data);

        } catch (\Exception $e){
            return api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Atualizar post de produto
     *
     * @param object $request
     * @return WP_REST_Response
     */
    public function update(object $request): WP_REST_Response
    {
        try{
            $postId = $request->get_param('id');

            if (empty(get_post($postId))) {
                throw new Exception('Não encontramos nenhum produto com esse ID', 422);
                wp_die();
            }
            $newValues = $request->get_params();
            wp_update_post($this->validade_params($newValues));

            return $this->api_response(array('Atualizado com sucesso', 200));

        } catch (\Exception $e){
            return $this->api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Criar post de produto
     *
     * @param object $request
     * @return WP_REST_Response
     */
    public function create(object $request): WP_REST_Response
    {
        try{
            $params = $request->get_params();

            if (!isset($params['title']) && empty($params['title'])) {
                throw new Exception("Parametro 'title' não informado", 404);
                wp_die();
            }

            wp_insert_post(array(
                'post_type' => 'post_product',
                'post_title' => $params['title'],
                'post_content' => $params['content'],
                'post_status' => 'publish',
            ));

            return $this->api_response(array('Novo post criado', 200));

        } catch (\Exception $e){
            return $this->api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Criar post de produto
     *
     * @param object $request
     * @return WP_REST_Response
     */
    public function delete(object $request): WP_REST_Response
    {
        try{
            $postId = $request->get_param('id');

            if (empty(get_post($postId))) {
                throw new Exception('Não encontramos nenhum produto com esse ID', 422);
                wp_die();
            }

            wp_delete_post($postId);

            return $this->api_response(array("Post do Id:{$postId} foi apagado", 200));

        } catch (\Exception $e){
            return $this->api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Validar os parametros passados
     *
     * @param array $params
     * @return array
     */
    private function validade_params(array $params)
    {
        return [
            'ID' => $params['id'],
            'post_title' => $params['title'] ? $params['title'] : get_the_title($params['id']),
            'post_content' => $params['content'] ? $params['content'] : get_the_content($params['id']),
        ];
    }

    /**
     * Gerar o response de listagem de produtos
     *
     * @param int $postId
     * @return array
     */
    private function posts_response(int $postId)
    {
        return array(
            'id' => $postId,
            'title' => get_the_title($postId),
            'content' => get_the_content(null, null, $postId),
            'createdAt' => get_the_date('Y-m-d H:i:s', $postId),
        );
    }

    /**
     * Tratar response da API
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @return WP_REST_Response
     */
    private function api_response(array $data, int $statusCode = 200, array $headers = [])
    {
        return new WP_REST_Response($data, $statusCode, $headers);
    }


    function add_routs_products()
    {
        /**
         * ROTA: Listar post de produtos
         *
         * @param paged página atual (opcional)
         * @param perPage Número de post por página (opcional)
         */
        register_rest_route( "/v{$this->version}", "/{$this->plugin_name}", array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'list'],
        ));

        /**
         * ROTA: Atualizar post de produto
         *
         * @param ID - (Number) - ID post
         * @param title atualizar título (opcional)
         * @param content atualizar conteúdo (opcional)
         *
         * Caso não seja enviado nenhum parametro, conteúdo será mantido
         */
        register_rest_route( "/v{$this->version}", "/{$this->plugin_name}/(?P<id>\d+)", array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => [$this, 'update'],
            'args' => array(
                'id' => array(
                    'validate_callback' => 'is_numeric',
                ),
            ),
        ));

        /**
         * ROTA: Criar post de produto
         *
         * @param title - Criar titulo (obrigatório)
         * @param content Criar conteúdo (opcional)
         *
         */
        register_rest_route( "/v{$this->version}", "/{$this->plugin_name}", array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'create'],
            'args' => array(
                'title' => array(
                    'validate_callback' => 'required',
                ),
            ),
        ));

        /**
         * ROTA: Deletar post de produto
         *
         * @param ID - (Number) - ID post
         *
         */
        register_rest_route( "/v{$this->version}", "/{$this->plugin_name}/(?P<id>\d+)", array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => [$this, 'delete'],
            'args' => array(
                'id' => array(
                    'validate_callback' => 'is_numeric',
                ),
            ),
        ));
    }

}
