import * as React from "react";
import { Container } from "react-bootstrap";
import {
  PolSEO,
  PolB2BHeader,
  PolB2BSuperBanner,
  PolHowToHelpYou,
  PolIdols,
  PolHowItWork,
  PolFaq,
  PolB2BForm,
  PolB2BFooter,
  PolB2BCases,
} from "components";

const IndexPage = () => {
  return (
    <>
      <PolSEO />
      <main>
        <Container fluid>
          <PolB2BHeader />
          <PolB2BSuperBanner />
          <PolHowToHelpYou />
          <PolIdols />
          <PolHowItWork />
          <PolB2BCases />
          <PolFaq />
          <PolB2BForm />
          <PolB2BFooter />
        </Container>
      </main>
    </>
  );
};

export default IndexPage;
