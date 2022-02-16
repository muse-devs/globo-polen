import * as React from "react";
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
} from "components";

const IndexPage = () => {
  return (
    <>
      <PolSEO />
      <main>
        <PolB2BHeader />
        <PolB2BSuperBanner />
        <PolHowToHelpYou />
        <PolIdols />
        <PolHowItWork />
        <PolFaq />
        <PolB2BForm />
        <PolB2BFooter />
      </main>
    </>
  );
};

export default IndexPage;
