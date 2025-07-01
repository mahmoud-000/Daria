export const formatNumber = (number, dec = 2) => {
    const value = (typeof number === "string"
        ? number
        : number.toString()
    ).split(".");

    if (dec <= 0) return value[0];
    let formated = value[1] || "";
    if (formated.length > dec) return `${value[0]}.${formated.substr(0, dec)}`;
    while (formated.length < dec) formated += "0";
    return `${value[0]}.${formated}`;

};
