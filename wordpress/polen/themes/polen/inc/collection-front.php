<?php

/**
 * Esse arquivo é responsavel por pegar qualquer
 * dado do banco de dados para o Front
 */



/**
 * Pegar os artistas recentes informando qual o maximo de resultado
 *
 * @param int quantity
 * @return array
 */
function polen_get_new_talents(int $quantity = 4)
{
	$talents = [
		[
			'ID' => '182',
			'talent_url' => '#/annita',
			'image' => 'https://i.ibb.co/QHwQ3Mj/Captura-de-Tela-2021-02-19-a-s-00-09-30.png',
			'url' => 'url',
			'price' => '700',
			'price_formatted' => 'R$ 700,00',
			'name' => 'Anitta',
			'category' => 'Cantora',
			'category_url' => '#/tag/cantora'
		],
		[
			'ID' => '12',
			'talent_url' => '#/fabio-pochat',
			'image' => 'https://i.ibb.co/k6qNWms/20201015115025451971u.jpg',
			'url' => 'url',
			'price' => '440',
			'price_formatted' => 'R$ 440,00',
			'name' => 'Fabio Porchat',
			'category' => 'Apresentador',
			'category_url' => '#/tag/apresentador'
		],
		[
			'ID' => '33',
			'talent_url' => '#/renato-aragao',
			'image' => 'https://i.ibb.co/L9HTH6d/Captura-de-Tela-2021-02-18-a-s-23-59-48.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Renato Aragão',
			'category' => 'Humorista',
			'category_url' => '#/tag/humorista'
		],
		[
			'ID' => '75',
			'talent_url' => '#/grazi-massafera',
			'image' => 'https://i.ibb.co/1LLcwhV/Captura-de-Tela-2021-02-19-a-s-00-06-00.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Grazi Massafera',
			'category' => 'BBB5',
			'category_url' => '#/tag/bbb5'
		],
	];
	return $talents;
}


/**
 * Pegar as categorias que serao apresentadas na Home
 *
 * @param int quantity
 * @return array
 */
function get_categories_home(int $quantity = 4)
{
	return [
		[
			'ID' => '332',
			'title' => 'Humor',
			'url' => '#/tag/humor',
			'image' => 'http://lorempixel.com/278/144/abstract/1/'
		],
		[
			'ID' => '234',
			'title' => 'Sertaneijo',
			'url' => '#/tag/sertanejo',
			'image' => 'http://lorempixel.com/278/144/abstract/2/'
		],
		[
			'ID' => '4',
			'title' => 'Funk',
			'url' => '#/tag/funk',
			'image' => 'http://lorempixel.com/278/144/abstract/3/'
		],
		[
			'ID' => '109',
			'title' => 'Balé',
			'url' => '#/tag/bale',
			'image' => 'http://lorempixel.com/278/144/abstract/4/'
		],
	];
}

/**
 * Pegar todos os artistas informando qual o maximo de resultado
 *
 * @param int quantity
 * @return array
 */
function polen_get_talents(int $quantity = 4)
{
	$talents = [
		[
			'ID' => '182',
			'talent_url' => '#/annita',
			'image' => 'https://i.ibb.co/QHwQ3Mj/Captura-de-Tela-2021-02-19-a-s-00-09-30.png',
			'url' => 'url',
			'price' => '700',
			'price_formatted' => 'R$ 700,00',
			'name' => 'Anitta',
			'category' => 'Cantora',
			'category_url' => '#/tag/cantora'
		],
		[
			'ID' => '12',
			'talent_url' => '#/fabio-pochat',
			'image' => 'https://i.ibb.co/k6qNWms/20201015115025451971u.jpg',
			'url' => 'url',
			'price' => '440',
			'price_formatted' => 'R$ 440,00',
			'name' => 'Fabio Porchat',
			'category' => 'Apresentador',
			'category_url' => '#/tag/apresentador'
		],
		[
			'ID' => '33',
			'talent_url' => '#/renato-aragao',
			'image' => 'https://i.ibb.co/L9HTH6d/Captura-de-Tela-2021-02-18-a-s-23-59-48.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Renato Aragão',
			'category' => 'Humorista',
			'category_url' => '#/tag/humorista'
		],
		[
			'ID' => '75',
			'talent_url' => '#/grazi-massafera',
			'image' => 'https://i.ibb.co/1LLcwhV/Captura-de-Tela-2021-02-19-a-s-00-06-00.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Grazi Massafera',
			'category' => 'BBB5',
			'category_url' => '#/tag/bbb5'
		],
		[
			'ID' => '182',
			'talent_url' => '#/annita',
			'image' => 'https://i.ibb.co/QHwQ3Mj/Captura-de-Tela-2021-02-19-a-s-00-09-30.png',
			'url' => 'url',
			'price' => '700',
			'price_formatted' => 'R$ 700,00',
			'name' => 'Anitta',
			'category' => 'Cantora',
			'category_url' => '#/tag/cantora'
		],
		[
			'ID' => '12',
			'talent_url' => '#/fabio-pochat',
			'image' => 'https://i.ibb.co/k6qNWms/20201015115025451971u.jpg',
			'url' => 'url',
			'price' => '440',
			'price_formatted' => 'R$ 440,00',
			'name' => 'Fabio Porchat',
			'category' => 'Apresentador',
			'category_url' => '#/tag/apresentador'
		],
		[
			'ID' => '33',
			'talent_url' => '#/renato-aragao',
			'image' => 'https://i.ibb.co/L9HTH6d/Captura-de-Tela-2021-02-18-a-s-23-59-48.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Renato Aragão',
			'category' => 'Humorista',
			'category_url' => '#/tag/humorista'
		],
		[
			'ID' => '75',
			'talent_url' => '#/grazi-massafera',
			'image' => 'https://i.ibb.co/1LLcwhV/Captura-de-Tela-2021-02-19-a-s-00-06-00.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Grazi Massafera',
			'category' => 'BBB5',
			'category_url' => '#/tag/bbb5'
		],
		[
			'ID' => '33',
			'talent_url' => '#/renato-aragao',
			'image' => 'https://i.ibb.co/L9HTH6d/Captura-de-Tela-2021-02-18-a-s-23-59-48.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Renato Aragão',
			'category' => 'Humorista',
			'category_url' => '#/tag/humorista'
		],
		[
			'ID' => '75',
			'talent_url' => '#/grazi-massafera',
			'image' => 'https://i.ibb.co/1LLcwhV/Captura-de-Tela-2021-02-19-a-s-00-06-00.png',
			'url' => 'url',
			'price' => '122',
			'price_formatted' => 'R$ 122,00',
			'name' => 'Grazi Massafera',
			'category' => 'BBB5',
			'category_url' => '#/tag/bbb5'
		],
	];
	return $talents;
}
