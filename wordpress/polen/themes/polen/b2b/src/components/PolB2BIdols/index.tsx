import React from "react";
import { Row, Col, Form } from "react-bootstrap";
import Slider from "react-slick";
import { ArrowLeft, ArrowRight } from "react-feather";
import "./styles.scss";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const idols = [
  { name: "Lazinho", img: "https://polen.me/polen/uploads/2022/01/806bf11b-7460-4b31-92e2-468b8a4a6724.jpeg" },
  { name: "João Gordo", img: "https://polen.me/polen/uploads/2022/02/IMG_0498.jpg" },
  { name: "Vanessa", img: "https://polen.me/polen/uploads/2021/08/Vanessa-Gerbelli-1.jpeg" },
  { name: "Bruno Chelles", img: "https://polen.me/polen/uploads/2021/06/IMG-20210429-WA0035.jpg" },
  { name: "Gleice", img: "https://polen.me/polen/uploads/2021/10/IMG_3903-1-2.jpg" },
  { name: "Papatinho", img: "https://polen.me/polen/uploads/2021/06/youknowmyface_Papatinho_2021-15-4-2.jpg" },
  { name: "Lazinho", img: "https://polen.me/polen/uploads/2022/01/806bf11b-7460-4b31-92e2-468b8a4a6724.jpeg" },
  { name: "João Gordo", img: "https://polen.me/polen/uploads/2022/02/IMG_0498.jpg" },
  { name: "Vanessa", img: "https://polen.me/polen/uploads/2021/08/Vanessa-Gerbelli-1.jpeg" },
  { name: "Bruno Chelles", img: "https://polen.me/polen/uploads/2021/06/IMG-20210429-WA0035.jpg" },
  { name: "Gleice", img: "https://polen.me/polen/uploads/2021/10/IMG_3903-1-2.jpg" },
  { name: "Papatinho", img: "https://polen.me/polen/uploads/2021/06/youknowmyface_Papatinho_2021-15-4-2.jpg" },
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
  infinite: true,
  speed: 500,
  centerMode: false,
  variableWidth: true,
  slidesToScroll: 1,
  nextArrow: <SampleNextArrow />,
  prevArrow: <SamplePrevArrow />
};

export default function () {
  return (
    <section>
      <Row className="py-3 g-0 my-5">
        <Col md={12}>
          <h2 className="typo-xxl text-center mb-4">
            Ídolos da Polen
          </h2>
        </Col>
        <Col md={12}>
          <Form>
            <div className="categories-list d-flex justify-content-center flex-wrap mb-5 py-4">
              <Form.Check
                inline
                label="Todos"
                name="filter-idols"
                type={'radio'}
              />
              <Form.Check
                inline
                label="Esporte"
                name="filter-idols"
                type={'radio'}
              />
              <Form.Check
                inline
                label="Apresentadores"
                name="filter-idols"
                type={'radio'}
              />
              <Form.Check
                inline
                label="Música"
                name="filter-idols"
                type={'radio'}
              />
              <Form.Check
                inline
                label="Dubladores"
                name="filter-idols"
                type={'radio'}
              />
            </div>
          </Form>
        </Col>
        <Col>
          <Slider {...settings}>
            {idols.map((item, key) => (
              <div key={key}>
                <CardIdol data={item} key={key} />
              </div>
            ))}
          </Slider>
        </Col>
      </Row>
    </section>
  );
}

function CardIdol({ data }) {
  return (
    <section>
      <div
        className="super-banner-item"
      >
        <figure className="video-card">
          <img
            src={data.img}
            alt={data.name}
            className="poster"
          />
        </figure>
        <div className="idol-name">
          <h3 className={"mb-0 typo-lg"}><strong>{data.name}</strong></h3>
        </div>
      </div>
    </section>
  );
}
