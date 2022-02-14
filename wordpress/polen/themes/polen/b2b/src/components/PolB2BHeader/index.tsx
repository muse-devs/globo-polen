import React from "react";
import { ChevronLeft } from "react-feather";
import { Container, Row, Col, Button } from "react-bootstrap";
import logo from "images/logo-b2b.png";

const PolB2bHeader = () => {
  return (
    <Container fluid>
      <Row className="py-4">
        <Col xs={6} className="d-flex align-items-center">
          <a href="/">
            <ChevronLeft />
            <img src={logo} alt="Logo B2B" width={114} />
          </a>
        </Col>
        <Col xs={6} className="d-flex align-items-center justify-content-end">
          <Button variant="outline-light" href="#fale">
            Fale Conosco
          </Button>
        </Col>
      </Row>
    </Container>
  );
};

export default PolB2bHeader;
