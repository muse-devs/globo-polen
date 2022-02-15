import React from "react";
import "./styles.scss";

export default function PolScrollable({ id, children }) {
  return (
    <section id={id} className="banner-scrollable">
      <div className="banner-scrollable__content ps-4">{children}</div>
      <div className="banner-scrollable__nav d-none d-md-block">nav</div>
    </section>
  );
}
