import React from "react";
import { Row, Col, Form } from "react-bootstrap";
import { PolScrollable } from "components";
import "./styles.scss";

const idols = [
  { name: "Lazinho", img: "https://polen.me/polen/uploads/2022/01/806bf11b-7460-4b31-92e2-468b8a4a6724.jpeg" },
  { name: "João Gordo", img: "https://polen.me/polen/uploads/2022/02/IMG_0498.jpg" },
  { name: "Vanessa", img: "https://polen.me/polen/uploads/2021/08/Vanessa-Gerbelli-1.jpeg" },
  { name: "Bruno Chelles", img: "https://polen.me/polen/uploads/2021/06/IMG-20210429-WA0035.jpg" },
  { name: "Gleice", img: "https://polen.me/polen/uploads/2021/10/IMG_3903-1-2.jpg" },
  { name: "Papatinho", img: "https://polen.me/polen/uploads/2021/06/youknowmyface_Papatinho_2021-15-4-2.jpg" },
]

export default function () {
  return (
    <section className="py-5">
      <Row>
        <Col md={12}>
          <h2 className="typo-xxl text-center mb-4">
            Ídolos da Polen
          </h2>
        </Col>
        <Col md={12}>
          <Form>
            <div className="categories-list d-flex justify-content-center flex-wrap">
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
          <PolScrollable id={"idols-list"}>
            {idols.map((item, key) => (
              <section key={`item-${key}`}>
                <div
                  id={`item-${key}`}
                  className="super-banner-item"
                >
                  <figure className="video-card">
                    <img
                      src={item.img}
                      alt={item.name}
                      className="poster"
                    />
                  </figure>
                  <div className="idol-name">
                    <h3 className={"mb-0 typo-lg"}><strong>{item.name}</strong></h3>
                  </div>
                </div>
              </section>
            ))}
          </PolScrollable>
        </Col>
      </Row>
    </section>
  );
}
