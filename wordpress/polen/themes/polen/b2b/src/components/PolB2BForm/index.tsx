import React from "react";
import { Row, Col, Form, Button } from "react-bootstrap";
import { useAppContext } from "context";
import { showMessage, MESSAGE_TYPES } from "components/PolMessage";
import { PolNonce, PolPreloader } from "components";
import { getError, getURLParam } from "utils";
import { ContactB2B } from "interfaces";
import { contactFormB2B } from "services";

export default function () {
  const context = useAppContext();
  const [formData, setFormData] = React.useState<ContactB2B | undefined>({
    company: "",
    email: "",
    form_id: "1",
    name: "",
    phone: "",
    security: "",
    slug_product: getURLParam("talent"),
  });

  const [preload, setPreload] = React.useState(false);

  const handleChange = (evt) => {
    setFormData((current) => ({
      ...current,
      [evt.target.name]: evt.target.value,
    }));
  };

  const handleSubmit = (evt) => {
    evt.preventDefault();
    setPreload(true);
    contactFormB2B(formData)
      .then((res) => {
        showMessage(
          context,
          MESSAGE_TYPES.MESSAGE,
          "Enviado",
          "Mensagem enviada com sucesso. Em breve entraremos em contato"
        );
      })
      .catch((err) => {
        showMessage(context, MESSAGE_TYPES.ERROR, "", getError(err));
      })
      .finally(() => {
        setPreload(false);
      });
  };

  return (
    <section
      id="faleconosco"
      className="form-b2b"
      style={{ position: "relative" }}
    >
      <Row className="px-3 px-md-5 g-0">
        <Col md={8} lg={6} className="m-md-auto">
          <h2 className="typo-xxl text-center">Fale Conosco</h2>
          <p className="typo-xs text-left text-md-center">
            Nosso time está ansioso para falar com você!
          </p>
          <Form onSubmit={handleSubmit}>
            <input
              type="text"
              name="name"
              placeholder="Nome Completo"
              className="form-control mt-4"
              onChange={handleChange}
              required
            />
            <input
              type="text"
              name="company"
              placeholder="Empresa"
              className="form-control mt-4"
              onChange={handleChange}
              required
            />
            <input
              type="email"
              name="email"
              placeholder="e-mail de trabalho"
              className="form-control mt-4"
              onChange={handleChange}
              required
            />
            <input
              type="tel"
              name="phone"
              placeholder="Número de telefone"
              className="form-control mt-4"
              onChange={handleChange}
              required
            />
            <div className="d-grid gap-2 mt-4 pt-1">
              <Button type="submit" size="lg">
                Enviar
              </Button>
            </div>
            <PolNonce onChange={handleChange} />
          </Form>
        </Col>
      </Row>
      {preload ? <PolPreloader local={true} /> : null}
    </section>
  );
}
