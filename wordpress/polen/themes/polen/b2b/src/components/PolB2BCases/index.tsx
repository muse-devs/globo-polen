import React from "react";
import { Row, Col } from "react-bootstrap";
import Slider from "react-slick";
import { ArrowLeft, ArrowRight } from "react-feather";
import { Calendar } from "react-feather";
import "./styles.scss";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const videosData = [
  {
    image:
      "https://i.vimeocdn.com/video/1364305989-55d3b1bef407347f5c2c527cf9db5b00bf9e80daf4d5dfe26081140cb47999ad-d",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/634757715/rendition/720p?loc=external&oauth2_token_id=1511985459&signature=11f36c28ddac629de6b3f726d073bca4c9a4a3847530345c6eb53258bfdbaaf6",
    name: "Edenred",
    text: `<p>
            Com a nossa solução para eventos, a Edenred escolheu 3 ídolos para
            anunciar premiações internas da empresa durante seu evento anual
            Celebre 2021.
          </p>
          <p>
            Rafael Infante, Nelson Freitas e Supla foram as celebridades
            escolhidas para apresentar os prêmios, parabenizar os funcionários
            de longa data e anunciar o vencedor do concurso de
            intraempreendedorismo.
          </p>`,
    paused: true,
  },
  {
    image:
      "https://i.vimeocdn.com/video/1364305989-55d3b1bef407347f5c2c527cf9db5b00bf9e80daf4d5dfe26081140cb47999ad-d",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/634757715/rendition/720p?loc=external&oauth2_token_id=1511985459&signature=11f36c28ddac629de6b3f726d073bca4c9a4a3847530345c6eb53258bfdbaaf6",
    name: "item2",
    text: `<p>
            Com a nossa solução para eventos, a Edenred escolheu 3 ídolos para
            anunciar premiações internas da empresa durante seu evento anual
            Celebre 2021.
          </p>
          <p>
            Rafael Infante, Nelson Freitas e Supla foram as celebridades
            escolhidas para apresentar os prêmios, parabenizar os funcionários
            de longa data e anunciar o vencedor do concurso de
            intraempreendedorismo.
          </p>`,
    paused: true,
  },
  {
    image:
      "https://i.vimeocdn.com/video/1364305989-55d3b1bef407347f5c2c527cf9db5b00bf9e80daf4d5dfe26081140cb47999ad-d",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/634757715/rendition/720p?loc=external&oauth2_token_id=1511985459&signature=11f36c28ddac629de6b3f726d073bca4c9a4a3847530345c6eb53258bfdbaaf6",
    name: "item2",
    text: `<p>
            Com a nossa solução para eventos, a Edenred escolheu 3 ídolos para
            anunciar premiações internas da empresa durante seu evento anual
            Celebre 2021.
          </p>
          <p>
            Rafael Infante, Nelson Freitas e Supla foram as celebridades
            escolhidas para apresentar os prêmios, parabenizar os funcionários
            de longa data e anunciar o vencedor do concurso de
            intraempreendedorismo.
          </p>`,
    paused: true,
  },
];

function SampleNextArrow(props) {
  const { onClick } = props;
  return (
    <div className="arrow next-arrow" onClick={onClick}>
      <ArrowRight />
    </div>
  );
}

function SamplePrevArrow(props) {
  const { onClick } = props;
  return (
    <div className="arrow prev-arrow me-3" onClick={onClick}>
      <ArrowLeft />
    </div>

  );
}

const settings = {
  dots: true,
  infinite: true,
  speed: 500,
  slidesToShow: 1,
  slidesToScroll: 1,
  fade: true,
  nextArrow: <SampleNextArrow />,
  prevArrow: <SamplePrevArrow />,
  responsive: [
    {
      breakpoint: 900,
      settings: {
       dots: false,
      }
    }
  ]
};

export default function () {
  return (
    <section className="cases-b2b mb-5">
      <Row className="g-0 p-3 px-md-5">
        <Col md={12} className="m-md-auto">
          <h2 className="typo-xxl text-center mb-5">Histórias de Sucesso</h2>
        </Col>
        <Col md={12}>
          <Slider {...settings}>
            {videosData.map((item, key) => (
              <div key={key}>
                <CardCase data={item} key={key} />
              </div>
            ))}
          </Slider>
        </Col>
      </Row>
    </section>
  );
}

function CardCase({ data }) {
  return (
    <section className="card-case col-xs-12 col-sm-10 mx-auto">
      <div className="card-case__wrapp">
        <Row className="g-0">
          <Col md={5} className="d-flex align-items-center">
            <figure className="video-player">
              <img src={data.image} alt={data.name} className="poster" />
              <video src={data.video} width={"100%"}></video>
            </figure>
          </Col>
          <Col md={7}>
            <div className="p-md-4">
              <p className="typo-md d-flex align-items-center mb-4">
                <Calendar color="var(--bs-primary)" className="me-2" />
                Evento
              </p>
              <h4 className="typo-lg mb-4">{data.name}</h4>
              <span
                dangerouslySetInnerHTML={{ __html: data.text }}
                className="typo-xs"
              />
            </div>
          </Col>
        </Row>
      </div>
    </section>
  );
}
