import React from "react";
import { Row, Col, Button } from "react-bootstrap";

const videos = [
  {
    image: "",
    video: "",
    logo: "",
    name: "",
  },
  {
    image: "",
    video: "",
    logo: "",
    name: "",
  },
];

export default function () {
  return (
    <section>
      <Row>
        <Col md={6} className="ps-4">
          <h1 className="typo-xl">
            Use os vídeos personalizados dos ídolos da Polen para impulsionar o
            seu <em>negócio</em>
          </h1>
          <p>
            Crie autoridade para sua marca, aumente suas vendas, e crie mais
            engajamento com seus clientes e colaboradores.
          </p>
          <Button href="#faleconosco">Fale com a equipe de vendas</Button>
        </Col>
        <Col md={6}>
          <section>
            <div>
              {videos.map((item) => (
                <div className="item">
                  <figure className="video-card">
                    <img src="" alt="" className="poster" />
                    <video src="" className="video-player"></video>
                  </figure>
                  <figure className="logo">
                    <img src="" alt="" className="image" />
                    <figcaption className="name">Marca</figcaption>
                  </figure>
                </div>
              ))}
            </div>
            <div>nav</div>
          </section>
        </Col>
      </Row>
    </section>
  );
}
