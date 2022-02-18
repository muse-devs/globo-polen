import React from "react";
import { getNonce } from "services";

export default function () {
  const [nonce, setNonce] = React.useState(null);
  React.useEffect(() => {
    getNonce().then(setNonce);
  }, []);
  return <input type="hidden" name="security" defaultValue={nonce} />;
}
