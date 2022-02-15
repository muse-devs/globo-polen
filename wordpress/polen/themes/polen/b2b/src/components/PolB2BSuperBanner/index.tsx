import React from "react";
import { Row, Col, Button } from "react-bootstrap";
import { PolScrollable } from "components";
import kovi from "images/logos/kovi.png";
import "./styles.scss";

const videosData = [
  {
    image:
      "https://i.vimeocdn.com/video/1364305989-55d3b1bef407347f5c2c527cf9db5b00bf9e80daf4d5dfe26081140cb47999ad-d",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/634757715/rendition/720p?loc=external&oauth2_token_id=1511985459&signature=11f36c28ddac629de6b3f726d073bca4c9a4a3847530345c6eb53258bfdbaaf6",
    logo: kovi,
    name: "item1",
    paused: true,
  },
  {
    image:
      "https://i.vimeocdn.com/video/1364305989-55d3b1bef407347f5c2c527cf9db5b00bf9e80daf4d5dfe26081140cb47999ad-d",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/634757715/rendition/720p?loc=external&oauth2_token_id=1511985459&signature=11f36c28ddac629de6b3f726d073bca4c9a4a3847530345c6eb53258bfdbaaf6",
    logo: kovi,
    name: "item2",
    paused: true,
  },
];

export default function () {
  const [videos, setVideos] = React.useState(videosData);

  const handleClick = (evt, key) => {
    console.log(evt.currentTarget);
    const video: HTMLVideoElement = document.querySelector(`#super-banner-video-${key}`);

    setVideos((current) => {
      return current.map((item, index) => ({
        ...item,
        paused: key == index ? false : true,
      }));
    });
    // video.play();
  };

  return (
    <section>
      <Row>
        <Col md={6} className="ps-4 mt-4 mt-md-0 order-1 order-md-0">
          <h1 className="typo-xl">
            Use os vídeos personalizados dos ídolos da Polen para impulsionar o
            seu <em>negócio</em>
          </h1>
          <p className="typo-xs">
            Crie autoridade para sua marca, aumente suas vendas, e crie mais
            engajamento com seus clientes e colaboradores.
          </p>
          <div className="d-grid gap-2 mt-4">
            <Button href="#faleconosco" size="lg">
              Fale com a equipe de vendas
            </Button>
          </div>
        </Col>
        <Col md={6}>
          <PolScrollable id={"super-banner"}>
            {videos.map((item, key) => (
              <section>
                <div
                  id={`super-banner-item-${key}`}
                  className="super-banner-item me-4"
                  key={`item-${key}`}
                  onClick={(evt) => handleClick(evt, key)}
                >
                  <figure className="video-card">
                    {videos[key].paused ? (
                      <img
                        src={item.image}
                        alt={item.name}
                        className="poster"
                      />
                    ) : null}
                    <video
                      id={`super-banner-video-${key}`}
                      src={item.video}
                      className={`video-player${
                        videos[key].paused ? " d-none" : ""
                      }`}
                      playsInline
                    ></video>
                  </figure>
                  {videos[key].paused ? (
                    <figure className="logo">
                      <img src={item.logo} alt={item.name} className="image" />
                      <figcaption className="name">{item.name}</figcaption>
                    </figure>
                  ) : null}
                </div>
              </section>
            ))}
          </PolScrollable>
        </Col>
      </Row>
    </section>
  );
}
