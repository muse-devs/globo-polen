import React from "react";
import { Row, Col, Button } from "react-bootstrap";
import { playVideo } from "services";
import kovi from "images/logos/kovi.png";
import tecmundo from "images/logos/logo-tecmundo.png";
import prestex from "images/logos/prestex.jpg";
import Slider from "react-slick";
import { ArrowLeft, ArrowRight } from "react-feather";
import "./styles.scss";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const videosData = [
  {
    image:
      "https://i.vimeocdn.com/video/1386366167-1ca2d94a134ddfe1c96de8a1eeeb2baf2a7ba0117367426e798275eeae1e78b4-d_240",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/683905373/rendition/540p?loc=external&signature=57ee9edec345249a784f17b97483e18d73c6ea91eddda79706af06db6258ae56",
    logo: kovi,
    name: "Kovi",
    paused: true,
  },
  {
    image:
      "https://i.vimeocdn.com/video/1386366167-1ca2d94a134ddfe1c96de8a1eeeb2baf2a7ba0117367426e798275eeae1e78b4-d_240",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/683905373/rendition/540p?loc=external&signature=57ee9edec345249a784f17b97483e18d73c6ea91eddda79706af06db6258ae56",
    logo: tecmundo,
    name: "Tecmundo",
    paused: true,
  },
  {
    image:
      "https://i.vimeocdn.com/video/1386366167-1ca2d94a134ddfe1c96de8a1eeeb2baf2a7ba0117367426e798275eeae1e78b4-d_240",
    video:
      "https://player.vimeo.com/progressive_redirect/playback/683905373/rendition/540p?loc=external&signature=57ee9edec345249a784f17b97483e18d73c6ea91eddda79706af06db6258ae56",
    logo: prestex,
    name: "Prestex",
    paused: true,
  },
];

function SampleNextArrow(props) {
  const { onClick } = props;
  return (
    <div className="arrow next-arrow me-3" onClick={onClick}>
      <ArrowRight />
    </div>
  );
}

function SamplePrevArrow(props) {
  const { onClick } = props;
  return (
    <div className="arrow prev-arrow me-4" onClick={onClick}>
      <ArrowLeft />
    </div>

  );
}

const settings = {
  dots: false,
  infinite: false,
  speed: 500,
  centerMode: false,
  variableWidth: true,
  slidesToScroll: 1,
  nextArrow: <SampleNextArrow />,
  prevArrow: <SamplePrevArrow />,
  responsive: [
    {
      breakpoint: 900,
      settings: {
        arrows: false,
      }
    }
  ]
};

export default function () {
  const [videos, setVideos] = React.useState(videosData);

  const handleClick = (evt, key) => {
    const video: HTMLVideoElement = document.querySelector(
      `#super-banner-video-${key}`
    );
    if (!video.paused) {
      video.pause();
      setVideos((current) => {
        return current.map((item, index) => ({
          ...item,
          paused: true,
        }));
      });
      return;
    }

    setVideos((current) => {
      return current.map((item, index) => ({
        ...item,
        paused: key == index ? false : true,
      }));
    });

    playVideo(video);

  };

  return (
    <section>
      <Row className="g-0 p-3">
        <Col md={12} lg={6} className="p-md-5 mt-4 mt-md-4 order-1 order-md-0">
          <h1 className="typo-xl title-b2b">
            Use os vídeos personalizados dos ídolos da Polen para impulsionar o
            seu <em className="title-b2b-highlight">negócio</em>
          </h1>
          <p className="typo-xs">
            Crie autoridade para sua marca, aumente suas vendas, e crie mais
            engajamento com seus clientes e colaboradores.
          </p>
          <Row>
            <Col lg={8} className="m-auto m-md-0">
              <div className="d-grid gap-2 mt-4">
                <Button href="#faleconosco" size="lg">
                  Fale com a equipe de vendas
                </Button>
              </div>
            </Col>
          </Row>
        </Col>
        <Col md={12} lg={6}>
          <Slider {...settings} className="videos-list">
            {videos.map((item, key) => (
              <div key={key}>
                <section key={`item-${key}`}>
                  <div
                    id={`super-banner-item-${key}`}
                    className={`super-banner-item${key == videos.length ? "" : " me-3"}`}
                    onClick={(evt) => handleClick(evt, key)}
                  >
                    <figure className="video-card">
                      <img src={item.image} alt={item.name} className="poster" />
                      <video
                        id={`super-banner-video-${key}`}
                        src={item.video}
                        className={`video-player${!videos[key].paused ? " active" : ""
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
              </div>
            ))}
          </Slider>
        </Col>
      </Row>
    </section>
  );
}
