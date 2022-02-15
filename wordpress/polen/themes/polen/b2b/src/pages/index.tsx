import * as React from "react";
import { PolSEO, PolB2BHeader, PolB2BSuperBanner } from "components";

const IndexPage = () => {
  return (
    <>
      <PolSEO />
      <main>
        <PolB2BHeader />
        <PolB2BSuperBanner />
      </main>
    </>
  );
};

export default IndexPage;
