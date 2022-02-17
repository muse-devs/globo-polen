import React from "react";
import { Row, Col } from "react-bootstrap";
import { PolScrollable } from "components";
import { Calendar } from "react-feather";
import "./styles.scss";

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

export default function () {
  return (
    <section className="cases-b2b">
      <Row className="g-0 p-3 px-md-5">
        <Col md={12} className="m-md-auto">
          <h2 className="typo-xxl text-center">Histórias de Sucesso</h2>
        </Col>
        <Col md={12} className="mt-5">
          <PolScrollable id={"cases-b2b"}>
            {videosData.map((item, key) => (
              <CardCase data={item} key={key} />
            ))}
          </PolScrollable>
        </Col>
      </Row>
    </section>
  );
}

function CardCase({ data }) {
  return (
    <section className="card-case col-md-4 col-12">
      <div className="card-case__wrapp">
        <Row className="g-0">
          <Col md={5}>
            <figure className="video-player">
              <img src={data.image} alt={data.name} className="poster" />
              <video src={data.video} width={"100%"}></video>
            </figure>
          </Col>
          <Col md={7}>
            <p className="typo-md d-flex align-items-center">
              <Calendar color="var(--bs-primary)" className="me-2" />
              Evento
            </p>
            <h4 className="typo-md">{data.name}</h4>
            <span
              dangerouslySetInnerHTML={{ __html: data.text }}
              className="typo-xs"
            />
          </Col>
        </Row>
      </div>
    </section>
  );
}
