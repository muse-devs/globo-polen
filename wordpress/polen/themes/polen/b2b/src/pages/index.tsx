import * as React from "react";
import {
  PolSEO,
  PolB2BHeader,
  PolB2BSuperBanner,
  PolHowToHelpYou,
  PolHowItWork,
  PolB2BForm,
} from "components";

const IndexPage = () => {
  return (
    <>
      <PolSEO />
      <main>
        <PolB2BHeader />
        <PolB2BSuperBanner />
        <PolHowToHelpYou />
        <PolHowItWork />
        <PolB2BForm />
      </main>
    </>
  );
};

export default IndexPage;
