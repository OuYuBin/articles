import org.eclipse.swt.*;
import org.eclipse.swt.widgets.*;

public class HelloWorld {

public static void main(String[] args) {
	Display display = new Display();

	/* 
	 * Create a Shell with the default style
	 * i.e. full screen, no decoration on PocketPC.
	 */
	Shell shell = new Shell(display);

	/* 
	 * Set a text so that the top level Shell
	 * also appears in the PocketPC task list
	 */
	shell.setText("Main");

	/*
	 * Set a menubar to follow UI guidelines
	 * on PocketPC
	 */
	Menu mb = new Menu(shell, SWT.BAR);
	shell.setMenuBar(mb);

	shell.open();
	while (!shell.isDisposed()) {
		if (!display.readAndDispatch())
			display.sleep();
	}
	display.dispose();
}
}
