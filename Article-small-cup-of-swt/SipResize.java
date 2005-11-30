import org.eclipse.swt.*;
import org.eclipse.swt.widgets.*;
import org.eclipse.swt.layout.*;

public class SipResize {

public static void main(String[] args) {
	Display display = new Display();
	Shell shell = new Shell(display, SWT.RESIZE);
	shell.setLayout(new FillLayout());
	shell.setText("Main");
	Menu mb = new Menu(shell, SWT.BAR);
	shell.setMenuBar(mb);
	Text text = new Text(shell, SWT.MULTI | SWT.V_SCROLL);
	String buffer = "";
	for (int i = 0; i < 100; i++) {
		buffer += "This is line " + i + "\r\n";
	}
	text.setText(buffer);

	shell.open();
	while (!shell.isDisposed()) {
		if (!display.readAndDispatch())
			display.sleep();
	}
	display.dispose();
}
}
