import React from "react";
import { Row, Col, Form, Button } from "react-bootstrap";

export default function () {
  return (
    <section className="form-b2b">
      <Row className="px-3 px-md-5 g-0">
        <Col md={8} lg={6} className="m-md-auto">
          <h2 className="typo-xxl text-center">Fale Conosco</h2>
          <p className="typo-xs text-left text-md-center">
            Nosso time está ansioso para falar com você!
          </p>
          <Form>
            <input
              type="text"
              name=""
              placeholder="Nome Completo"
              className="form-control mt-4"
            />
            <input
              type="text"
              name=""
              placeholder="Empresa"
              className="form-control mt-4"
            />
            <input
              type="email"
              name=""
              placeholder="e-mail de trabalho"
              className="form-control mt-4"
            />
            <input
              type="tel"
              name=""
              placeholder="Número de telefone"
              className="form-control mt-4"
            />
            <div className="d-grid gap-2 mt-4 pt-1">
              <Button href="#faleconosco" size="lg">
                Enviar
              </Button>
            </div>
          </Form>
        </Col>
      </Row>
    </section>
  );
}
