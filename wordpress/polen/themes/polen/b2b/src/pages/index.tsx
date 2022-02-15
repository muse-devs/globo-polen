import * as React from "react";
import { PolSEO, PolB2BHeader, PolB2BSuperBanner, PolHowToHelpYou } from "components";

const IndexPage = () => {
  return (
    <>
      <PolSEO />
      <main>
        <PolB2BHeader />
        <PolB2BSuperBanner />
        <PolHowToHelpYou />
      </main>
    </>
  );
};

export default IndexPage;
