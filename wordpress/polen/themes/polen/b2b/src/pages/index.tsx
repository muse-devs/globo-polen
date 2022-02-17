import * as React from "react";
import { Container } from "react-bootstrap";
import {
  PolSEO,
  PolB2BHeader,
  PolB2BSuperBanner,
  PolB2BHelpYou,
  PolB2BIdols,
  PolB2BHowItWork,
  PolB2BPartners,
  PolB2BFaq,
  PolB2BForm,
  PolB2BFooter,
  PolB2BCases,
} from "components";

const IndexPage = () => {
  return (
    <>
      <PolSEO />
      <main>
        <Container fluid className="px-0">
          <PolB2BHeader />
          <PolB2BSuperBanner />
          <PolB2BHelpYou />
          <PolB2BIdols />
          <PolB2BHowItWork />
          <PolB2BCases />
          <PolB2BPartners />
          <PolB2BFaq />
          <PolB2BForm />
          <PolB2BFooter />
        </Container>
      </main>
    </>
  );
};

export default IndexPage;
