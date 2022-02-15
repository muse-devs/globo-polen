import * as React from "react";
import {
  PolSEO,
  PolB2BHeader,
  PolB2BSuperBanner,
  PolHowToHelpYou,
  PolHowItWork
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
      </main>
    </>
  );
};

export default IndexPage;
