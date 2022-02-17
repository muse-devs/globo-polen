import React from "react";
import { Row, Col } from "react-bootstrap";
import { PolScrollable } from "..";
import logo from "images/logo-company.png";
import ceo from "images/ceo.png";
import "./styles.scss";

const partners = [
  { logo: logo, message: 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', name: "Jane Cooper", position: "CEO - Edenred", avatar: ceo },
  { logo: logo, message: 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', name: "Jane Cooper", position: "CEO - Edenred", avatar: ceo },
  { logo: logo, message: 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', name: "Jane Cooper", position: "CEO - Edenred", avatar: ceo },
  { logo: logo, message: 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', name: "Jane Cooper", position: "CEO - Edenred", avatar: ceo },
  { logo: logo, message: 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', name: "Jane Cooper", position: "CEO - Edenred", avatar: ceo },
  { logo: logo, message: 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', name: "Jane Cooper", position: "CEO - Edenred", avatar: ceo },
]

export default function () {
  return (
    <section>
      <Row className="p-3 p-md-5 g-0">
        <Col md={12} className="d-block d-sm-none">
          <h2 className="typo-xxl text-center">
            Palavras dos nossos parceiros
          </h2>
        </Col>
        <Col sm={12} className="mt-5">
          <PolScrollable id="partners-list">
            {partners.map((item, key) => (
              <Col xs={12} md={4} key={key}>
                <CardPartner data={item} key={key} />
              </Col>
            ))}
          </PolScrollable>
        </Col>
      </Row>
    </section>
  );
}

function CardPartner({ data }) {
  return (
    <section className="me-md-3">
      <Row className="g-0">
        <Col xs={12}>
          <div className="box-color p-4 mb-4">
            <Row>
              <Col sm={12} className="d-flex justify-content-center mb-3">
                <img src={data.logo} alt="Logo B2B" width={160} />
              </Col>
              <Col sm={12}>
                <p className="mb-4 typo-xs">{data.message}</p>
              </Col>
              <Col sm={12} className="d-flex justify-content-center mb-3">
                <img src={data.avatar} alt={data.name} width={48} className="rounded-circle" />
              </Col>
              <Col sm={12} className="mb-3">
                <h5 className={"text-center typo-xs mb-1"}>{data.name}</h5>
                <h5 className={"text-center typo-xs"}>{data.position}</h5>
              </Col>
            </Row>
          </div>
        </Col>
      </Row>
    </section>
  );
}
