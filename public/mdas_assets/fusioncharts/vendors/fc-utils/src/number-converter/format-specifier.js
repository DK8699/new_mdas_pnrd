const RE=/^(?:(.)?([<>=^]))?([+\-\( ])?([$#])?(0)?(\d+)?(,)?(\.\d+)?(~)?([a-z%])?$/i,isNil=a=>'undefined'==typeof a||null===a;class FormatSpecifier{constructor(a){const b=RE.exec(a);if(!b)throw new Error(`Invalid number format specifier: ${a}`);this.fill=b[1]||' ',this.align=b[2]||'>',this.sign=b[3]||'-',this.symbol=b[4]||'',this.zero=!!b[5],this.width=b[6]&&+b[6],this.comma=!!b[7],this.precision=b[8]&&+b[8].slice(1),this.trim=!!b[9],this.type=b[10]||''}toString(){var a=Math.max;return this.fill+this.align+this.sign+this.symbol+(this.zero?'0':'')+(isNil(this.width)?'':a(1,0|this.width))+(this.comma?',':'')+(isNil(this.precision)?'':'.'+a(0,0|this.precision))+(this.trim?'~':'')+this.type}}export default FormatSpecifier;